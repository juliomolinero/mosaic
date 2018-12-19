<?php
namespace User\Controller;

use User\Form\LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Authentication\Result;
use Zend\Uri\Uri;
use User\Model\UserReadDbInterface;
use User\Model\UserWriteDbInterface;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController{
    
    /**
     * 
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;
    /**
     * 
     * @var UserReadDbInterface
     */
    private $userReadDb;
    /**
     * 
     * @var UserWriteDbInterface
     */
    private $userWriteDb;
    /**
     *
     * @param \Zend\Authentication\AuthenticationService $authService
     * @param UserReadDbInterface $userReadDb
     * @param UserWriteDbInterface $userWriteDb
     * @param UserLogInterface $userLog
     */
    public function __construct(\Zend\Authentication\AuthenticationService $authService, UserReadDbInterface $userReadDb, UserWriteDbInterface $userWriteDb ){
        $this->authService = $authService;
        $this->userReadDb = $userReadDb;
        $this->userWriteDb = $userWriteDb;        
    }
    public function indexAction(){
        
    }    
    /**
     * Authenticates user given email address and password credentials.
     */
    public function loginAction()
    {
        // If user has already signed in, redirect to home page
        if( $this->authService->hasIdentity() ){
            return $this->redirect()->toRoute('home');
        }
        // Define layout for this
        $this->layout()->setTemplate('layout/auth');
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }
        // Create login form
        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);
        
        // Store login status.
        $isLoginError = false;
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            // Validate form
            if( $form->isValid() ) {
                
                // Get filtered and validated data
                $data = $form->getData();
                $login = $data['user'];
                
                // Perform login attempt.
                /** @var \Zend\Authentication\Adapter\DbTable $adapter */
                $adapter = $this->authService->getAdapter();
                $adapter->setIdentity( $login );
                $adapter->setCredential( md5($data['password']) );
                $adapter->getDbSelect()->where( ['active = ?' => 1] ); // MUST be an active account
                //echo ($adapter->getDbSelect()->getSqlString());
                $result = $this->authService->authenticate();
                
                // Check result.
                if ($result->isValid()) {
                    if( $data['remember_me'] ) {
                        // Remember for 7 days
                        $session = new SessionManager();                        
                        // Session cookie will expire in 1 month (7 days).
                        $session->rememberMe(60*60*24*7);                        
                    }
                    // Update last login date and store information in session
                    $this->updateLastLogin($login);
                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');
                    if (!empty($redirectUrl)) {
                        // The below check is to prevent possible redirect attack
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost()!=null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }
                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }            
        }        
        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);        
    }
    public function forbiddenAction() {
        // Dummy function, DO NOT remove !
    }
    /**
     * Clear user identity
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        // Clear identity
        $this->authService->clearIdentity();
        // Remove session
        $container = new Container('MosaicSession');
        $container->getManager()->destroy();
        $container->getManager()->getStorage()->clear('MosaicSession');
        // Redirect to login page
        return $this->redirect()->toRoute('login');
    }
    /**
     * Enable password reset function
     */
    public function resetPasswordAction(){
        // Define layout for this
        $this->layout()->setTemplate('layout/auth');
    }
    /**
     *
     * Update user last login date and also store information in session
     * @param string $login
     */
    private function updateLastLogin( $login ){
        $user = $this->userReadDb->findUserEmail($login);
        $this->userWriteDb->updateUserLastLogin($user);
        
        // Store user's information in session
        $container = new Container('MosaicSession');
        $container->userId = $user->getId();        
        $container->userEmail = $user->getEmail();
        $container->isAdmin = $user->getIsAdmin();        
    }
    
}
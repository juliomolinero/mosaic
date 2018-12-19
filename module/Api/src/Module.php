<?php
namespace Api;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Api\Model\ApiClientReadDbImpl;

class Module
{    
    public function getConfig() {
        return include  __DIR__ . '/../config/module.config.php';
    } 
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $uri = $e->getRequest()->getRequestUri();
        //var_dump( $uri );        
        //var_dump( $e->getRequest()->isXmlHttpRequest() );
        //var_dump( strpos($uri, '/api123/') );        
        //var_dump( $e->getRouteMatch()->getMatchedRouteName() );
        // Runs on API module only
        if( strpos($uri, '/api/')!==false ) {
            // Set custom JSON errors
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
            $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
            // Access Validation
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array( $this, 'beforeDispatch' ), 100);            
        }        
    }    
    /**
     * Is the client allowed to see this content ?
     * 
     * http://blog.mrezekiel.com/2012/11/zend-framework-2-rest-api.html
     * 
     *  // under this module check for application name and api key
     *  $serviceManager = $mvcEvent->getApplication()->getServiceManager();
     *  $sharedEvents = $mvcEvent->getApplication()->getEventManager()->getSharedManager();
     *  $sharedEvents->attach(__NAMESPACE__, 'dispatch', function(MvcEvent $mvcEvent) use ($serviceManager){
     *  $em = $serviceManager->get('doctrine.entitymanager.orm_default');
     *  $key = $mvcEvent->getRouteMatch()->getParam('key', null);
     *  $app = $mvcEvent->getRouteMatch()->getParam('app', null);
     *  $response = $mvcEvent->getResponse();
     *  $response->setStatusCode(403);
     *  if (!$key || !$app) {
     *      return $response;
     *  }
     *  $apiKeys = $em->getRepository('Application\Entity\ApiKeys');
     *  $apiKey = $apiKeys->findOneBy(array('appName' => $app));
     *  if (!$apiKey) {
     *      return $response;
     *  } elseif ($apiKey->key !== $key) {
     *      return $response;
     *  }
     *  });
     * 
     * @param MvcEvent $event
     */
    public function beforeDispatch(MvcEvent $event){        
        $request = $event->getRequest();
        $response = $event->getResponse();
        $requestUri = $request->getRequestUri();        
        // Grab params from route
        $params = $event->getRouteMatch()->getParams();        
        /**
         * Remove params from route
         * /api/upload/config/v1/julio.molinero@nttdata.com         
         * 
         * So we can have something like this
         * /api/upload/config/v1/         
         * 
         */
        foreach ( $params as $param ){            
            //var_dump( $param );
            $requestUri = str_replace( $param .'/', '', $requestUri);
            $requestUri = str_replace( $param, '', $requestUri);            
        }        
        // Get client credentials from headers
        $headers = $request->getHeaders();
        //var_dump( $headers );
        $app = $headers->get('App');
        $key = $headers->get('AppKey');
        // echo 'App->'.$app.'--AppKey->'.$key;
        if (!$key || !$app) {
            // Set status to forbidden
            $response->setStatusCode(403);                        
            // Send a custom error message
            $viewModel = new JsonModel( [
                'error' => 'Unauthorized', 
                'message' => 'Application or key missing'                
            ]);
            $event->setViewModel($viewModel);
            $event->stopPropagation(true);
            return $viewModel;            
        }
        $serviceManager = $event->getApplication()->getServiceManager();
        // Get DB adapter
        $dbAdapter = $serviceManager->get('Db\SlaveAdapter');
        // Create API client instance
        $apiClientList = new ApiClientReadDbImpl( $dbAdapter, new \Zend\Hydrator\Reflection() );        
        // Check permissions
        if( $apiClientList->findApiClientPermission($app->getFieldValue(), $key->getFieldValue(), $requestUri) ){
            $response->setStatusCode(200);
        } else {
            error_log("|ERROR| ".$app->getFieldValue()." has no permissions to consume this service: ".$requestUri);
            $response->setStatusCode(403);
            return $response;
        }        
    }
 
    // To avoid displaying detailed erros ============================ BEGIN
    // https://github.com/stevenalexander/zf2-restful-api
    public function onDispatchError($e)
    {
        return $this->getJsonModelError($e);
    }    
    public function onRenderError($e)
    {
        return $this->getJsonModelError($e);
    }
    public function getJsonModelError($e)
    {        
        $error = $e->getError();
        if (!$error) {
            return;
        }        
        $response = $e->getResponse();        
        $exception = $e->getParam('exception');
        $exceptionJson = array();                
        
        // My own implementation
        $exceptionJson['message'] = '';
        if ($exception) {
            $exceptionJson['message'] = $exception->getMessage();            
        }
        if ($error == 'error-router-no-match') {            
            $exceptionJson['message'] = 'Resource not found.';
        }               
        $model = new JsonModel( [ 'message'=>$exceptionJson['message'] ] );
        // My own implementation
            
        $e->setResult($model);    
        return $model;
    }
    // To avoid displaying detailed erros ============================ END
}
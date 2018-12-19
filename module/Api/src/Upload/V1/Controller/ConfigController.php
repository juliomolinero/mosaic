<?php
namespace Api\Upload\V1\Controller;

use Api\Upload\V1\Model\ConfigFileDbWriteInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Api\Upload\V1\Model\ConfigFile;

class ConfigController extends AbstractJsonServiceController
{
    /**
     * ConfigFileDbWriteInterface
     * @var ConfigFileDbWriteInterface
     */
    private $configFileDbWriteInterface;
    /**
     *
     * @var string
     */
    private $clientApp;
    /**
     * 
     * @param ConfigFileDbWriteInterface $configFileDbWriteInterface
     */
    public function __construct(ConfigFileDbWriteInterface $configFileDbWriteInterface ){
        $this->configFileDbWriteInterface = $configFileDbWriteInterface;        
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Zend\Mvc\Controller\AbstractController::setEventManager()
     */
    public function setEventManager( EventManagerInterface $events ){
        parent::setEventManager($events);
        $events->attach(MvcEvent::EVENT_DISPATCH, array( $this, 'beforeDispatch' ) , 100);
    }
    /**
     * Catch what HTTP METHOD is trying to be executed and if it's not in the list
     * will throw an exception
     *
     * @param MvcEvent $event
     */
    public function beforeDispatch(MvcEvent $event){
        $request = $event->getRequest();
        // Get client credentials from headers
        $headers = $request->getHeaders();
        $app = $headers->get('App');
        if( !$app ){
            $this->forbidden( 'Invalid credentials' );
        }
        //var_dump( $headers );
        $this->clientApp = $app->getFieldValue();
        // What methods are allowed to be executed by this controller
        $allowedMethods = [ 'post', 'get' ];
        $httpMethod = $event->getRequest()->getMethod();
        //        echo strtolower($httpMethod); // DEBUG only
        if( !in_array( strtolower($httpMethod), $allowedMethods ) ) {
            $this->methodNotAllowed();
        }        
    }
    /**
     * 
     * https://stackoverflow.com/questions/15246264/how-to-return-custom-http-status-code-in-zend-framework-2
     * 
     * * POST method maps to create, see more details at 
     * https://framework.zend.com/manual/2.1/en/modules/zend.mvc.controllers.html
     * 
     * Add a record in our database
     * ==================================================================== 
     * DA TEST URL
     * ====================================================================
     * HEADERS:
     * App: mosaic
     * AppKey: 432c739d5e115ebab0eb726df9f254ad
     * Content-Type: application/json
     * 
     * METHOD: POST
     * @link http://localhost:8090/api/upload/config/v1
     * 
     * HTTP REQUEST BODY PAYLOAD:
     * {
     *  "Sender": "julio.molinero@nttdata.com",
     *  "ConfigFiles": [
     *      { "Field1": "Value1", "Field2": "Value2", "Field3": "Value3" },
     *      { "Field1": "Value1", "Field2": "Value2", "Field3": "Value3" },
     *      { "Field1": "Value1", "Field2": "Value2", "Field3": "Value3" }
     *   ]
     * }
     * 
     *  Your controller already has a Response object, set the status code on that and just return
     *  if (!$this->isApiKeyValid()) {
     *      $this->getResponse()->setStatusCode(401);
     *      return;
     *  }
     * {@inheritDoc}
     * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
     */
    public function create($data){        
        $logId = time();
        $status = 'success';
        $debug = json_encode($data);
        // Perform validation
        if( !isset($data['ConfigFiles']) || !isset($data['Sender']) ){
            $status = 'error';
            error_log("|ErrorID:{$logId}|=== data ===\r\n".$debug."\r\n");
            $this->applicationError("|ErrorID:{$logId}|Invalid payload, an array of Logs is expected");
        }
        $counter = 0;
        try {
            $timeStamp = time();
            $sender = trim( strtolower($data['Sender']) );
            foreach ( $data["ConfigFiles"] as $log ){
                $jsonEncode = json_encode( $log, JSON_FORCE_OBJECT, 10 );
                $configFile = new ConfigFile(0, $sender, $jsonEncode);
                $configFile = $this->configFileDbWriteInterface->insert($configFile);
                $counter++;                
            }
            // All good ?, proceed to update the so called JSON column
            $this->configFileDbWriteInterface->updateToJson($timeStamp);
        } catch( \Exception $e ) {
            $status = 'error';
            error_log("|ErrorID:{$logId}|=== data ===\r\n".$debug."\r\n Exception Error ".$e->getMessage()."\r\n");
            $this->applicationError("|ErrorID:{$logId}|Could not add log to database");
        }
        // Send JSON response
        return new JsonModel([
            'status' => $status,
            'message' => "{$counter} configuration file(s) added"
        ]);        
    }
    /**
     * Get a list of configuration files by sender
     *     
     * ================================================================
     * DA TEST URL 
     * ================================================================
     * @link http://localhost:8090/api/config/v1/julio.molinero@nttdata.com
     * 
     * HEADERS:
     * App: mosaic
     * AppKey: 432c739d5e115ebab0eb726df9f254ad
     * 
     * METHOD: GET
     * 
     * {@inheritDoc}
     * @see \Zend\Mvc\Controller\AbstractRestfulController::get()
     */
    public function get($id){
        $results = [];
        try {
            $configFiles = $this->configFileDbWriteInterface->getAllBySender($id);
            if( !empty($configFiles) ){
                foreach ( $configFiles as $configFile ){                    
                    $item = [ 'ConfigFile' => $configFile['file_content_str'] ];
                    array_push( $results, $item );
                }
            }
        } catch ( \InvalidArgumentException $e) {
            $this->notFound( $e->getMessage() ); 
        } catch( \Exception $e ){
            $this->applicationError( $e->getMessage() );
        }        
        // Send JSON response
        return new JsonModel([
            'ConfigFiles' => $results,
        ]);        
    }    
}

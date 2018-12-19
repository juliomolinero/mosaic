<?php
namespace Api\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Hydrator\HydrationInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;

class ApiClientReadDbImpl implements ApiClientReadDbInterface
{
    // Properties
    // Define table names
    /**
     *
     * @var string
     */
    protected $_apiClient = 'api_clients';
    /**
     *
     * @var string
     */
    protected $_apiClientPermission = 'api_clients_permission';
    // Define prototypes
    /**
     *
     * @var ApiClient $_apiClientPrototype
     */
    private $_apiClientPrototype;
    /**
     *
     * @var ApiClientPermission $_apiClientPermissionPrototype
     */
    private $_apiClientPermissionPrototype;
    /**
     * @var AdapterInterface
     */
    private $db;
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    // Constructor
    public function __construct(Adapter $adapter, HydrationInterface $hydrator){
        $this->db = $adapter;
        $this->hydrator = $hydrator;
        // Set dummy company prototype
        $this->_apiClientPrototype = new ApiClient(0, '', '', '', 0);
        $this->_apiClientPermissionPrototype = new ApiClientPermission(0, 0, '');
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Model\ApiClientReadDbInterface::findAllApiClient()
     */
    public function findAllApiClient($paginated = false){
        $sql = new Sql($this->db);
        $select = $sql->select( $this->_apiClient );
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        // Are we getting what is expected?
        if ( !$result instanceof ResultInterface || !$result->isQueryResult() ) {
            return [];
        }
        $resultSet = new HydratingResultSet($this->hydrator, $this->_apiClientPrototype);
        // Are we using pagination ?
        if( $paginated ){
            $resultSet->setObjectPrototype( $this->_apiClientPrototype );
            // Create a new pagination adapter object:
            $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->db, $resultSet);
            $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
            return $paginator;
        } else {
            $resultSet->initialize($result);
            return $resultSet;
        }        
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Model\ApiClientReadDbInterface::findApiClient()
     */
    public function findApiClient($id){
        $sql       = new Sql($this->db);
        $select    = $sql->select( $this->_apiClient );
        $select->where(['id = ?' => $id]);        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();        
        if ( !$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \RuntimeException(sprintf('Failed retrieving api client with identifier "%s"; unknown database error.',$id));            
        }
        $resultSet = new HydratingResultSet($this->hydrator, $this->_apiClientPrototype);
        $resultSet->initialize($result);
        $apiClient = $resultSet->current();
        if ( !$apiClient ) {
            throw new \InvalidArgumentException(sprintf('Api client with identifier "%s" not found.',$id));
        }
        return $apiClient;
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Model\ApiClientReadDbInterface::findApiClientPermission()
     */
    public function findApiClientPermission($appLogin, $appKey, $uri) : bool {
        $sql       = new Sql($this->db);
        $select    = $sql->select( $this->_apiClient )->columns( ['login'] );
        $select->join( $this->_apiClientPermission, "{$this->_apiClient}.id={$this->_apiClientPermission}.client_id", [ 'uri' ]);
        $select->where( ['login = ?' => $appLogin]);
        $select->where( [ "{$this->_apiClient}.key = ?" => $appKey]);
        $select->where( ['uri = ?' => $uri]);
        $statement = $sql->prepareStatementForSqlObject($select);
        // Get SQL String, DEBUG ONLY !!!
        //        echo $sql->buildSqlString( $select, $this->db ); die();
        $result    = $statement->execute();
        if ( !$result instanceof ResultInterface || !$result->isQueryResult() ) {
            throw new \RuntimeException(sprintf('Failed retrieving api client with identifier "%s"; unknown database error.',$appLogin));
        }
        // No records found, client has no access to the URL
        if( $result->count()===0 ){
            return false;
        } else {
            return true;
        }        
    }
}


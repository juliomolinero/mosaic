<?php
namespace Api\Upload\V1\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Expression;

class ConfigFileDbWriteImpl implements ConfigFileDbWriteInterface
{
    /**
     *
     * @var AdapterInterface
     */
    private $db;
    /**
     *
     * @var string Table name
     */
    protected $_name = "config_files";    
    
    // Constructor
    public function __construct(Adapter $adapter){
        $this->db = $adapter;        
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Upload\V1\Model\ConfigFileDbWriteInterface::insert()
     */
    public function insert(ConfigFile $configFile){
        $insert = new Insert( $this->_name );
        $insert->values([
            'id' => $configFile->getId(),
            'sent_by' => $configFile->getSentBy(),
            'file_content_str' => $configFile->getFileContentStr(),
            'file_content_json' => $configFile->getFileContentJson(),
            'date_created' => time()
        ]);
        $sql = new Sql( $this->db );
        $statement = $sql->prepareStatementForSqlObject($insert);
        // DEBUG ONLY
        //        echo $sql->buildSqlString( $insert ); die();
        $result = $statement->execute();
        if ( !$result instanceof ResultInterface ){
            throw new \RuntimeException('Database error occurred during config file insert operation');
        }
        $newId = $sql->getAdapter()->getDriver()->getLastGeneratedValue();
        return new ConfigFile( $newId, $configFile->getSentBy(), $configFile->getFileContentStr(), 
            $configFile->getFileContentJson(), $configFile->getDateCreated() );
        
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Upload\V1\Model\ConfigFileDbWriteInterface::updateToJson()
     */
    public function updateToJson($timestamp){
        $update = new Update( $this->_name );
        $update->set([
            'file_content_json' => new Expression('file_content_str')
         ]);
        $update->where([
            'date_created >= ?' => $timestamp,
            'JSON_VALID(file_content_str) = ?' => 1
        ]);
        $sql = new Sql( $this->db );
        $statement = $sql->prepareStatementForSqlObject($update);
        // DEBUG ONLY
        //        echo $sql->buildSqlString( $update ); die();
        $result = $statement->execute();
        if (! $result instanceof ResultInterface) {
            throw new \RuntimeException( 'Database error occurred during config file update operation' );
        }
    }
    /**
     * 
     * {@inheritDoc}
     * @see \Api\Upload\V1\Model\ConfigFileDbWriteInterface::getAllByUser()
     */
    public function getAllBySender($sentBy){
        if( empty($sentBy) ){
            throw new \InvalidArgumentException('Sent by name missing');
        }
        $sql = new Sql( $this->db );
        $select = $sql->select( $this->_name );
        $select->where([ 'sent_by = ?' => $sentBy ]);
        $statement = $sql->prepareStatementForSqlObject($select);
        // Get SQL String, DEBUG ONLY !!!
        //        echo $sql->buildSqlString( $select, $this->db ); die();
        $result = $statement->execute();
        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }
        return $result;        
    }
}
<?php
namespace User\Model;

use Zend\Db\Adapter\AdapterInterface;
use User\Model\UserReadDbInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;

class UserReadDbImpl implements UserReadDbInterface
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
    protected $_name = 'mosaic_users';
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    
    /**
     * @var User
     */
    private $userPrototype;
    /**
     * @param AdapterInterface $db
     */
    public function __construct(AdapterInterface $db, HydratorInterface $hydrator) {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->userPrototype = new User('', '');
    }
    /**
     * 
     * {@inheritDoc}
     * @see \User\Model\UserReadDbInterface::findUserEmail()
     */
    public function findUserEmail($email){
        $sql = new Sql( $this->db );
        $select    = $sql->select( $this->_name );
        $select->where(['email = ?' => $email]);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        // Get SQL String, DEBUG ONLY !!!
//        echo $sql->buildSqlString( $select, $this->db ); die();
        $result    = $statement->execute();
        if ( !$result instanceof ResultInterface || !$result->isQueryResult() ) {
            throw new \RuntimeException(sprintf('Failed retrieving user with email "%s"; unknown database error.',$email));
        }
        $resultSet = new HydratingResultSet( $this->hydrator, $this->userPrototype );
        $resultSet->initialize($result);
        $user = $resultSet->current();
        if (!$user) {
            throw new \InvalidArgumentException( sprintf('User with email "%s" not found.',$email) );
        }
        return $user;
    }
}


<?php
namespace User\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;

class UserWriteDbImpl implements UserWriteDbInterface
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
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter){
        $this->db = $adapter;
    }
    /**
     * 
     * {@inheritDoc}
     * @see \User\Model\UserWriteDbInterface::updateUserLastLogin()
     */
    public function updateUserLastLogin(User $user){
        if ( !$user->getId() ) {
            throw new \RuntimeException('Cannot update user; missing identifier');
        }
        $update = new Update($this->_name);
        $update->set( [ 'last_login' => time() ] );
        $update->where(['id = ?' => $user->getId()]);
        
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        
        if ( !$result instanceof ResultInterface){
            throw new \RuntimeException('Database error occurred during update user operation');
        }
        return $user;        
    }
    /**
     * 
     * {@inheritDoc}
     * @see \User\Model\UserWriteDbInterface::setNewPassword()
     */
    public function setNewPassword($userId, $newPwd){
        if ( !$userId ) {
            throw new RuntimeException('Cannot activate user; missing identifier');
        }
        $update = new Update( $this->_name );
        $update->set( [ 'password' => md5($newPwd), 'validation_code' => 0, 'validation_code_expires' => 0 ] );
        $update->where(['id = ?' => $userId ]);
        
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        
        if (! $result instanceof ResultInterface) {
            throw new RuntimeException('Database error occurred during set active user operation');
        }
    }
    /**
     * 
     * {@inheritDoc}
     * @see \User\Model\UserWriteDbInterface::setValidationCode()
     */
    public function setValidationCode($userId, $validationCode){
        if ( !$userId ) {
            throw new \RuntimeException('Cannot set validation code; missing identifier');
        }
        $validationCodeExpires = time() + (60 * 60 * 72); // Up to three days
        $update = new Update( $this->_name );
        $update->set( [ 'validation_code' => $validationCode, 'validation_code_expires'=>$validationCodeExpires ] );
        $update->where(['id = ?' => $userId ]);
        
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        
        if (! $result instanceof ResultInterface) {
            throw new \RuntimeException('Database error occurred during set validation code operation');
        }        
    }
}


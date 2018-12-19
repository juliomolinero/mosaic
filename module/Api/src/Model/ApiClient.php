<?php
namespace Api\Model;

/**
 * Entity definition for api_clients 
 * 
 * CREATE TABLE `api_clients` (
 *   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *   `login` varchar(45) NOT NULL,
 *   `name` varchar(240) NOT NULL,
 *   `key` varchar(80) NOT NULL,
 *   `created` int(11) NOT NULL,
 *   PRIMARY KEY (`id`),
 *   UNIQUE KEY `login_UNIQUE` (`login`),
 *   UNIQUE KEY `name_UNIQUE` (`name`),
 *   UNIQUE KEY `key_UNIQUE` (`key`)
 *   ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
 *   
 * 
 * @author Julio_MOLINERO
 *
 */
class ApiClient
{
    /**
     * 
     * @var number
     */
    private $id;
    /**
     * 
     * @var string
     */
    private $login;
    /**
     * 
     * @var string
     */
    private $name;
    /**
     * 
     * @var string Application Key
     */
    private $key;
    /**
     * 
     * @var number UNIX timestamp
     */
    private $created;
    
    // Constructor
    public function __construct( $id, $login, $name, $key, $created ){
        $this->id = $id;
        $this->login = $login;
        $this->name = $name;
        $this->key = $key;
        $this->created = $created;        
    }
    
    // Methods
    /**
     * 
     * @return number
     */
    public function getId(){
        return $this->id;        
    }
    /**
     * 
     * @return string
     */
    public function getLogin(){
        return $this->login;
    }
    /**
     * 
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    /**
     * 
     * @return string
     */
    public function getKey(){
        return $this->key;
    }
    /**
     * 
     * @return number
     */
    public function getCreated(){
        return $this->created;
    }    
}

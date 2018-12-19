<?php
namespace User\Model;

/**
 * Entity definition for mosaic_users
 *
 * CREATE TABLE `mosaic_users` (
 *   `id` int(11) NOT NULL AUTO_INCREMENT, 
 *   `password` varchar(40) NOT NULL,
 *   `email` varchar(255) NOT NULL,
 *   `created` int(11) NOT NULL,
 *   `last_login` int(11) NOT NULL,
 *   `is_admin` int(1) NOT NULL DEFAULT '0',
 *   `active` int(1) NOT NULL DEFAULT '1',
 *   `validation_code` varchar(20) DEFAULT NULL,
 *   `validation_code_expires` int(11) DEFAULT NULL, 
 *
 *  PRIMARY KEY (`email`),
 *  UNIQUE KEY `id` (`id`) 
 * ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
 *
 *
 * @author Julio_MOLINERO
 *
 */
class User
{
    // Properties
    /**
     * @var int
     */
    private $id;    
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $email;
    /**
     * @var int
     */
    private $created;
    /**
     * @var int
     */
    private $last_login;
    /**
     * @var int
     */
    private $is_admin;
    /**
     * @var int
     */
    private $active;
    /**
     * @var string
     */
    private $validation_code;
    /**
     * @var int
     */
    private $validation_code_expires;    
    
    // Constructor
    /**
     * 
     * @param string $password
     * @param string $email
     * @param number $created
     * @param number $last_login
     * @param number $is_admin
     * @param number $active
     * @param string $validation_code
     * @param number $validation_code_expires
     * @param number|null $id     
     */
    public function __construct($password, $email, $created = 0, $last_login = 0, $is_admin = 0, $active = 1,
        $validation_code = '', $validation_code_expires = 0, $id = 0) {
            $this->password = $password;
            $this->email = $email;
            $this->created = $created;
            $this->last_login = $last_login;
            $this->is_admin = $is_admin;
            $this->active = $active;
            $this->validation_code = $validation_code;
            $this->validation_code_expires = $validation_code_expires;
            $this->id = $id;
    }
    
    // Methods
    // Getter    
    /**
     *
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }
    /**
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }
    /**
     *
     * @return number
     */
    public function getCreated() {
        return $this->created;
    }
    /**
     *
     * @return number
     */
    public function getLastLogin() {
        return $this->last_login;
    }
    /**
     *
     * @return number
     */
    public function getIsAdmin() {
        return $this->is_admin;
    }
    /**
     *
     * @return number
     */
    public function getActive() {
        return $this->active;
    }
    /**
     *
     * @return string
     */
    public function getValidationCode() {
        //return $this->validation_code;
        return substr(md5(rand()), rand(0, 20), 20);
    }
    /**
     *
     * @return number
     */
    public function getValidationCodeExpires() {
        return $this->validation_code_expires;
    }
    /**
     *
     * @return number
     */
    public function getId(){
        return $this->id;
    }    
}
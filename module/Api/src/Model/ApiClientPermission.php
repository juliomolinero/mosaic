<?php
namespace Api\Model;

/**
 * Entity definition for api_clients_permission
 * 
 * CREATE TABLE `api_clients_permission` (
 *   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *   `client_id` int(10) unsigned NOT NULL,
 *   `uri` varchar(240) NOT NULL,
 *   PRIMARY KEY (`id`),
 *   KEY `api_clients_permission_idx` (`client_id`),
 *   CONSTRAINT `api_clients_permission` FOREIGN KEY (`client_id`) 
 *   REFERENCES `api_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
 *   ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
 *   
 * @author Julio_MOLINERO
 *
 */
class ApiClientPermission
{
    /**
     * 
     * @var number
     */
    private $id;
    /**
     * 
     * @var number
     */
    private $client_id;
    /**
     * 
     * @var string The URI's the client has access to
     */
    private $uri;
    // Constructor
    public function __construct( $id, $client_id, $uri ){
        $this->id = $id;
        $this->client_id = $client_id;
        $this->uri = $uri;
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
     * @return number
     */
    public function getClientId(){
        return $this->client_id;
    }
    /**
     * 
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }
}
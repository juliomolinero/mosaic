<?php
namespace Api\Upload\V1\Model;

/**
 * Entity definition for config_files
 * 
 * CREATE TABLE `config_files` (
 *   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *   `sent_by` varchar(240) NOT NULL,
 *   `file_content_str` mediumtext NOT NULL,
 *   `file_content_json` json DEFAULT NULL,
 *   `date_created` int(11) NOT NULL,
 *   PRIMARY KEY (`id`)
 *   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
 *   
 *
 */
class ConfigFile
{
    // Properties
    /**
     * 
     * @var number
     */
    private $id;
    /**
     * 
     * @var string
     */
    private $sent_by;
    /**
     * 
     * @var string
     */
    private $file_content_str;
    /**
     * 
     * @var string to be parsed as in JSON format
     */
    private $file_content_json;
    /**
     * 
     * @var number
     */
    private $date_created;
    
    // Constructor
    public function __construct($id, $sentBy, $fileContentStr, $fileContentJson = null, $dateCreated = 0) {
        $this->id = (integer)$id;
        $this->sent_by = substr($sentBy, 0, 255);
        $this->file_content_str = $fileContentStr;
        $this->file_content_json = $fileContentJson;
        $this->date_created = $dateCreated;
    }
    
    // Methods

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $sent_by
     */
    public function getSentBy()
    {
        return $this->sent_by;
    }

    /**
     * @return the $file_content_str
     */
    public function getFileContentStr()
    {
        return $this->file_content_str;
    }

    /**
     * @return the $file_content_json
     */
    public function getFileContentJson()
    {
        return $this->file_content_json;
    }

    /**
     * @return the $date_created
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $sent_by
     */
    public function setSentBy($sent_by)
    {
        $this->sent_by = $sent_by;
    }

    /**
     * @param string $file_content_str
     */
    public function setFileContentStr($file_content_str)
    {
        $this->file_content_str = $file_content_str;
    }

    /**
     * @param string $file_content_json
     */
    public function setFileContentJson($file_content_json)
    {
        $this->file_content_json = $file_content_json;
    }
    
    
}


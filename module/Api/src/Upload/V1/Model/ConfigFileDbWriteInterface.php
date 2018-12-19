<?php
namespace Api\Upload\V1\Model;

interface ConfigFileDbWriteInterface
{
    /**
     * Add a record to our database
     * @param ConfigFile $configFile
     */
    public function insert(ConfigFile $configFile);
    /**
     * Perform updates on those columns that are valid JSON objects
     * @param integer $timestamp UnixTimeStamp
     */
    public function updateToJson($timestamp);
    /**
     * Get a list of all configuration files sent by a user
     * @param string $sentBy
     * @return ConfigFile[]
     */
    public function getAllBySender($sentBy);
}
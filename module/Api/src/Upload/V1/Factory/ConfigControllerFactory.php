<?php
namespace Api\Upload\V1\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Api\Upload\V1\Controller\ConfigController;
use Api\Upload\V1\Model\ConfigFileDbWriteInterface;

class ConfigControllerFactory implements FactoryInterface
{
    /**
     * Inject interface to be able to write to the database
     * 
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null ){
        return new ConfigController( $container->get(ConfigFileDbWriteInterface::class) );
    }
}


<?php
namespace Api\Upload\V1\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Api\Upload\V1\Model\ConfigFileDbWriteImpl;

/**
 * To handle more than one database adapters
 * https://docs.zendframework.com/tutorials/db-adapter/
 *
 * Sometimes you may need multiple adapters.
 * As an example, if you work with a cluster of databases, one may allow write operations,
 * while another may be read-only.
 *
 * Retrieving named adapters
 *
 * Retrieve named adapters in your service factories just as you would another service:
 *
 * function ($container) {
 *     return new SomeServiceObject($container->get('Db\SlaveAdapter));
 * }
 *
 * See line: return new ConfigFileDbWriteImpl( $container->get('Db\MainAdapter') ...
 * 
 * @author Julio Molinero
 *
 */
class ConfigFileDbWriteFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        return new ConfigFileDbWriteImpl( $container->get('Db\MainAdapter') );
    }
}


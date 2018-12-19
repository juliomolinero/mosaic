<?php
namespace Api\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Api\Model\ApiClientReadDbImpl;
use Zend\Hydrator\Reflection as ReflectionHydrator;

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
 * See line: return new ApiClientZendDbSqlList( $container->get('Db\SlaveAdapter') ...
 *
 * @author Julio_MOLINERO
 *
 */
class ApiClientReadDbImplFactory implements FactoryInterface{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null ){
        return new ApiClientReadDbImpl( $container->get('Db\SlaveAdapter'), new ReflectionHydrator() );        
    }
}
<?php
namespace User\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use User\Model\UserReadDbImpl;

class UserReadDbFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options=null){
        return new UserReadDbImpl( $container->get('Db\SlaveAdapter'), new ReflectionHydrator());
    }
}


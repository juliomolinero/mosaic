<?php
namespace User\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use User\Model\UserWriteDbImpl;

class UserWriteDbFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options=null){
        return new UserWriteDbImpl( $container->get('Db\MainAdapter'));
    }    
}


<?php
namespace User\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use User\Controller\AuthController;
use User\Model\UserReadDbInterface;
use User\Model\UserWriteDbInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        // Create the authentication adapter
        /** @var \Zend\Db\Adapter\Adapter $dbAdapter */
        $adapter = new \Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter(
            $container->get('Db\SlaveAdapter'),
            'mosaic_users', // Table name
            'email', // Identity column
            'password' // Credential column
            );
        $adapter->getDbSelect()->where('active = 1'); // MUST be an active account
        // Create the storage adapter
        $storage = new \Zend\Authentication\Storage\Session();
        // Finally create the service
        $authService = new \Zend\Authentication\AuthenticationService($storage, $adapter);
        // Create controller instance and inject objects
        return new AuthController($authService, $container->get(UserReadDbInterface::class), $container->get(UserWriteDbInterface::class) );
    }
}


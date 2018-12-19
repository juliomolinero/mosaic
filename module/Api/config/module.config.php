<?php
namespace Api;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

use Api\Upload\V1\Controller\ConfigController;
use Api\Model\ApiClientReadDbInterface;
use Api\Model\ApiClientReadDbImpl;
use Api\Upload\V1\Model\ConfigFileDbWriteInterface;
use Api\Upload\V1\Model\ConfigFileDbWriteImpl;
use Api\Factory\ApiClientReadDbImplFactory;
use Api\Upload\V1\Factory\ConfigFileDbWriteFactory;
use Api\Upload\V1\Factory\ConfigControllerFactory;

return [
    'service_manager' => [
        'aliases' => [
            ApiClientReadDbInterface::class => ApiClientReadDbImpl::class,
            ConfigFileDbWriteInterface::class => ConfigFileDbWriteImpl::class,
        ],
        'factories' => [
            ApiClientReadDbImpl::class => ApiClientReadDbImplFactory::class,
            ConfigFileDbWriteImpl::class => ConfigFileDbWriteFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ConfigController::class => ConfigControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_exceptions' => true,
        'display_not_found_reason' => true,
        // DO NOT forget to add your proper module name !!
        'template_path_stack' => [
            'api' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'router' => [
        'routes' => [
            'api' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/api" as uri:
                    'route' => '/api',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => ConfigController::class,
                     ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    '/' => [ // Listen to "/api/" as uri with the forward slash at the end
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'controller' => ConfigController::class,
                            ],
                        ],
                    ],
                    'upload-config-v1' => [ // Listen to "/api/upload/config/v1/:id" as uri
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/upload/config/v1[/:id]',
                            'defaults' => [
                                'controller' => ConfigController::class,
                            ],                            
                        ],
                    ],
                ],
            ],
        ],
    ],
];
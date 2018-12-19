<?php
namespace User;

// Needed for routing
use Zend\Router\Http\Literal;

return [
    'service_manager' => [
        'aliases' => [
            Model\UserReadDbInterface::class => Model\UserReadDbImpl::class,
            Model\UserWriteDbInterface::class => Model\UserWriteDbImpl::class,
        ],
        'factories' => [
            Model\UserReadDbImpl::class => Factory\UserReadDbFactory::class,
            Model\UserWriteDbImpl::class => Factory\UserWriteDbFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
        ],
    ],
    'view_manager' => [
        // DO NOT forget to add your proper module name !!
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'router' => [
        'routes' => [
            'login' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/login" as uri:
                    'route' => '/login',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/logout" as uri:
                    'route' => '/logout',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'forbidden' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/forbidden" as uri:
                    'route' => '/forbidden',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'forbidden',
                    ],
                ],
            ],
            'reset-password' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/reset-password" as uri:
                    'route' => '/reset-password',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'reset-password',
                    ],
                ],
            ],
            'user' => [
            // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/user" as uri:
                    'route' => '/user',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    '/' => [ // Listen to "/user/" as uri with the forward slash at the end
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],                    
                ],
            ],
        ],
    ],
];
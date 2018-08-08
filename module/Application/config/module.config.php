<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;


use Doctrine\ORM\Mapping\Driver\AnnotationDriver;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'order' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/add-order',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'add-order',
                    ],
                ],
            ],

            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action' => 'admin',
                    ],
                ],
            ],

            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'resetPassword',
                    ],
                ],
            ],
            'set-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/set-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'setPassword',
                    ],
                ],
            ],
            'users' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                ],
            ],


            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],

        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CurrentUserPlugin::class => Controller\Plugin\Factory\CurrentUserPluginFactory::class,
        ],
        'aliases' => [
            'currentUser' => Controller\Plugin\CurrentUserPlugin::class,
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // Фильтр доступа может работать в 'ограничительном' (рекомендуется) или 'разрешающем'
            // режиме. В ограничительном режиме все действия контроллера должны быть явно перечислены
            // под ключом конфигурации 'access_filter', а доступ к любому не перечисленному действию
            // для неавторизованных пользователей запрещен. В разрешающем режиме, даже если действие не
            // указано под ключом 'access_filter', доступ к нему разрешен для всех (даже для
            // неавторизованных пользователей. Рекомендуется использовать более безопасный ограничительный режим.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['index', 'about'], 'allow' => '*']
            ],
            Controller\AdminController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                ['actions' => ['admin'], 'allow' => '@'],
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\TestimonialManager::class => Service\Factory\TestimonialManagerFactory::class,
            Service\CommanderManager::class => Service\Factory\CommanderManagerFactory::class,
            Service\PartnerManager::class => Service\Factory\PartnerManagerFactory::class,
            Service\CounterManager::class => Service\Factory\CounterManagerFactory::class,
            Service\AlbumManager::class => Service\Factory\AlbumManagerFactory::class,
            Service\PhotoManager::class => Service\Factory\PhotoManagerFactory::class,
            Service\ShopItemManager::class => Service\Factory\ShopItemManagerFactory::class,
            Service\ShopItemGistManager::class => Service\Factory\ShopItemGistManagerFactory::class,
            Service\ShopItemParameterManager::class => Service\Factory\ShopItemParameterManagerFactory::class,
            Service\OrderManager::class => Service\Factory\OrderManagerFactory::class,
            Service\FlagManager::class => Service\Factory\FlagManagerFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/login_layout' => __DIR__ . '/../view/layout/login_layout.phtml',
            'layout/admin_layout' => __DIR__ . '/../view/layout/admin_layout.phtml',
            'layout/layout_mt' => __DIR__ . '/../view/layout/layout_mt.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\CurrentUser::class => View\Helper\Factory\CurrentUserFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
            View\Helper\Menu::class => InvokableFactory::class,
        ],
        'aliases' => [
            'currentUser' => View\Helper\CurrentUser::class,
            'adminMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ]
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/Migrations',
                'name' => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table' => 'migrations'
            ]
        ],
    ],
    'view_helper_config' => [
        'flashMessenger' => [
            'message_open_format' => '<div%s><ul><li>',
            'message_close_string' => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ]
    ],
];

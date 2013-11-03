<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

return array(
    'router' => array(
        'routes' => array(
            'cis_api_get' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/cis/api/get[/:id[/:dimension]]',
                    'defaults' => array(
                        'controller' => 'Cisapi\Controller\Api',
                        'action'     => 'get',
                        'id'		 => 0,
                        'dimension'	 => 'x',
                    ),
                ),
            ),
            'cis_api_getMeta' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/cis/api/getmeta[/:id]',
                    'defaults' => array(
                        'controller' => 'Cisapi\Controller\Api',
                        'action'  	 => 'getmeta',
                        'id'		 => 0,
                    ),
                ),
            ),
            'cis_api_set' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/cis/api/set[/:id]',
                    'defaults' => array(
                        'controller' => 'Cisapi\Controller\Api',
                        'action'     => 'set',
                        'id'		 => 0,
                    ),
                ),
            ),
            'cis_api_auth' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/cis/api/auth',
                    'defaults' => array(
                        'controller' => 'Cisapi\Controller\Api',
                        'action'     => 'auth',
                        'id'		 => 0,
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Cisapi\Controller\Api' => 'Cisapi\Controller\ApiController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'cisapi/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

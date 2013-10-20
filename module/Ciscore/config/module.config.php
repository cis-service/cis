<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

use Ciscore\Model\Image;
use Ciscore\Model\ImageTable;

return array(
    'router' => array(
        'routes' => array(
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            ),
        'initializers' => array(
            function ($instance, $sm) {
                if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                    $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                }
            }
        ),
        'factories' => array(
           'Ciscore\Model\ImageTable' =>  function($sm) {
                    $table = new ImageTable();
                    $table->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    //$table->setServiceMangager($sm);
                    return $table;
                },
            'Ciscore\Model\Image' => function ($sm) {
                    $img = new Image();
                    $img->setServiceManager($sm);
                    return $img;
                },
        ),
        'shared' => array(
            'Ciscore\Model\ImageTable' => true,
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
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

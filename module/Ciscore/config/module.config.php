<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
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
        'factories' => array(
            'Ciscore\Model\ImageTable' =>  function($sm) {
                    $tableGateway = $sm->get('ImageTableGateway');
                    $table = new ImageTable($tableGateway);
                    return $table;
                },
            'ImageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Image());
                    return new TableGateway('image', $dbAdapter, null, $resultSetPrototype);
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

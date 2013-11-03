<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

namespace Ciscore;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Ciscore\Model\Image;
use Ciscore\Model\ImageTable;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //Init ACL
        $this -> initAcl($e);
        $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function initAcl(MvcEvent $e) {
     
        $acl = new Acl();
        //$roles = include __DIR__ . '/config/module.acl.roles.php';
        $roles = $this->getDbRoles($e);
        $allResources = array();
        foreach ($roles as $role => $resources) {
     
            $role = new GenericRole($role);
            $acl -> addRole($role);
     
            $allResources = array_merge($resources, $allResources);
     
            //adding resources
            foreach ($resources as $resource) {
                 if(!$acl ->hasResource($resource))
                    $acl -> addResource(new GenericResource($resource));
            }
            //adding restrictions
            foreach ($allResources as $resource) {
                $acl -> allow($role, $resource);
            }
        }
        //testing
        //var_dump($acl->isAllowed('admin','home'));
        //true
     
        //setting to view
        $e -> getViewModel() -> acl = $acl;
     
    }
 
    public function checkAcl(MvcEvent $e) {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        error_log("Route: $route");
        //you set your role
        $userRole = 'guest';
     
        try{
            $isAllowed = $e -> getViewModel() -> acl -> isAllowed($userRole, $route);
            $response = $e -> getResponse();
        }
        catch(\Exception $err)
        {
            error_log($err->getMessage());
            //$response = $e->getMessage();
            $response = new \Zend\Http\Response();
            $response->setContent("<html><body>".$err->getMessage()."</body></html>");
            $isAllowed = false;
        }
        if (!$isAllowed) {
        //if ($e -> getViewModel() -> acl ->hasResource($route) && !$e -> getViewModel() -> acl -> isAllowed($userRole, $route)) {
            //$response = $e -> getResponse();
            //location to page or what ever
            $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
            $response -> setStatusCode(404);
        }
    }
    public function getDbRoles(MvcEvent $e){
        // I take it that your adapter is already configured
        $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $results = $dbAdapter->query('SELECT * FROM rules left join role ON (rules.roleId = role.id) left join user (rules.userId = user.Id)');
        // making the roles array
        $roles = array();
        foreach($results as $result){
            $roles[$result['role.role']][] = $result['rules.resource'];
        }
        return $roles;
    }
}

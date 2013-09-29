<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cisapi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    public function getMetaAction()
    {
		$result = new JsonModel(array(
			'some_parameter' => 'some value',
            'id'=>$this->params()->fromRoute('id',false),
        ));
        return $result;
	}
    public function getAction()
    {
		$result = new JsonModel(array(
			'some_parameter' => 'some value',
            'id'=>$this->params()->fromRoute('id',false),
        ));
        return $result;
	}
}

<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

namespace Cisapi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Ciscore\Model\Image;
use Ciscore\Model\ImageTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class IndexController extends AbstractActionController
{
	protected $albumTable;
    public function getMetaAction()
    {
		$id = $this->getImageFromRoute();
		$result = array();
		if(!$id)
		{
			$result = array('success'=>false);
		}
		else
		{
			$img = $this->getImageTable()->getImage($id);
			if($img)
			{
				$result = array_merge($img->toArray(),array('success'=>true));
			}
			else
			{
				$result = array('success'=>false);
			}
		}
        return new JsonModel($result);
	}
    public function getAction()
    {
		$result = new JsonModel(array(
			'some_parameter' => 'some value',
            'id'=>$this->params()->fromRoute('id',false),
        ));
        return $result;
	}
	public function getImageTable()
    {
        if (!$this->imageTable) {
            $sm = $this->getServiceLocator();
            $this->imageTable = $sm->get('Ciscore\Model\ImageTable');
        }
        return $this->imageTable;
    }
	
	protected function getImageFromRoute()
	{
		//Get ID From Route
		$id = $this->params()->fromRoute('id',0);
		//Return False if not set or empty
		if(!$id)
			return false;
		
		return $id;
	}
}

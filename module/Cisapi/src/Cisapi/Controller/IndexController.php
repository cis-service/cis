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
	protected $imageTable;
    public function getMetaAction()
    {
		$img = $this->getImageFromRoute();
		
		if(!$img)
		{
			$result = array('success'=>false);
		}
		else
		{
			$result = array_merge($img->toArray(),array('success'=>true));
		}
        return new JsonModel($result);
	}
	/**
	 * @return Ciscore\Model\Image
	 **/
	protected function getImageFromRoute()
	{
		$id = $this->getImageIdFromRoute();
		$result = array();
		if($id)
		{
			$img = $this->getImageTable()->getImage($id);
			if($img)
			{
				return $img;
			}
			else
			{
				return false;
			}
		}
		return false;
	}
	
    public function getAction()
    {
		$img = $this->getImageFromRoute();
		if(!$img)
		{
			//Return empty img
			return;
		}
		$dimension = $this->params()->fromRoute('dimension',0);
		
		$response = $this->getResponse();
		$imageContent = file_get_contents($img->getImagePath($dimension));

		$response->setContent($imageContent);
		$response
			->getHeaders()
			->addHeaderLine('Content-Transfer-Encoding', 'binary')
			->addHeaderLine('Content-Type', $img->type)
			->addHeaderLine('Content-Info', json_encode($img->toArray()))
			->addHeaderLine('Content-Length', mb_strlen($imageContent));

		return $response;
	}
	
	public function setAction()
	{
		$request = $this->getRequest();
		$result = array('success'=>false);
		 if ($request->isPost()) {
			// Make certain to merge the files info!
			/*$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);*/
			$img = $this->getServiceLocator()->get('Ciscore\Model\Image');
			$files = $request->getFiles();
			if(count($files)!=1)
			{
				$result['message'] = "Wrong filecount";
				return new JsonModel($result);
			}
			$file = $files['file_contents'];
			//error_log(var_export($file,true));
			if($file['size']<100)
			{
				$result['message'] = "File uploaderror";
				return new JsonModel($result);
			}
			$id = intval($this->params()->fromRoute('id',0));
			if($id)
			{
				$img->id=$id;
			}
			$img->setTmpFilename($file['tmp_name']);
			$img->filename = $file['name'];
			$img->credit = $request->getPost('credit','');
			$img->title = $request->getPost('title','');
			try{
				$this->getImageTable()->saveImage($img);
				$result['success']=true;
				$result['id']=$img->id;
			}
			catch(Exception $e)
			{
				$result['message']= $e->getMessage();
			}
		}
        return new JsonModel($result);
	}
	
	public function getImageTable()
    {
        if (!$this->imageTable) {
            $sm = $this->getServiceLocator();
            $this->imageTable = $sm->get('Ciscore\Model\ImageTable');
        }
        return $this->imageTable;
    }
	
	protected function getImageIdFromRoute()
	{
		//Get ID From Route
		$id = $this->params()->fromRoute('id',0);
		//Return False if not set or empty
		if(!$id)
			return false;
		
		return $id;
	}
}

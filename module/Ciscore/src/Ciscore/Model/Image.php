<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

namespace Ciscore\Model;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Image implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;
    /**
     * Id of the Image
     * @var bigint imageid 
     */
    public $id;
    /**
     * Title of the image
     * @var string title 
     */
    public $title;
    public $type;
    public $filename;
    public $credit;
    
    public function save()
    {
        
    }

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->type  = (isset($data['type'])) ? $data['type'] : null;
        $this->filename  = (isset($data['filename'])) ? $data['filename'] : null;
        $this->credit  = (isset($data['credit'])) ? $data['credit'] : null;
    }
    
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'filename' => $this->filename,
            'credit' => $this->credit,
        );
    }
    
    public function getImagePath($dimension="x")
    {
        $dimension=explode('x',$dimension);
        if($dimension != 2)
        {
            $dimX=0;
            $dimY=0;
        }
        else
        {
            $dimX = int($dimension[0]);
            $dimY = int($dimension[1]);
        }
		if($dimX == 0 && $dimY==0) //Original Image needed
		{
			
		}
        $this->getImageBasepath();
    }
    
    public function setServiceManager(ServiceManager $sm)
    {
        $this->serviceManager=$sm;
    }
    
    private function getImageBasepath()
    {
        $options = $this->serviceManager->get('config');
		//var_dump($options['cis']['core']['imgpath']['original']);exit();
        $md5 = md5($this->id);
        $base = $options['cis']['core']['imgpath']['original'].substr(chunk_split($md5, 2, '/'), 0, -1); 
        echo $base;exit();
    }
    
}

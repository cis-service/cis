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
     * Revision of the image
     * @var int revision
     */
    public $revision;
    /**
     * Title of the image
     * @var string title 
     */
    public $title;
    /**
     * Type of the image
     * @var string type
     */
    public $type;
    /**
     * Original Filename
     * @var string Filename
     */
    public $filename;
    public $credit;
    
    private $tmpFilename=null;
    
    public function setTmpFilename($filename)
    {
        $this->tmpFilename = $filename;
    }
    public function getTmpFilename()
    {
        return $this->tmpFilename;
    }
    public function hasTmpFile()
    {
        return $this->tmpFilename !=null;
    }
    
    public function saveTmpFile()
    {
        $path = $this->getImagePath();
        if(!file_exists(dirname($path)))
        {
            if(!mkdir(dirname($path),0775,true))
            {
                throw new \Exception("Could not create path");
            }
        }
        move_uploaded_file($this->getTmpFilename(),$path);
        $this->type = $this->getTmpImagetype();
    }
    public function getTmpImagetype()
    {
        if(!$this->tmpFilename) return false;
        
        $path = $this->tmpFilename;
        if(!file_exists($path)) $path=$this->getImagePath();
        list($width, $height, $type, $attr) = getimagesize($path);
        return image_type_to_mime_type($type);
    }

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->revision     = (isset($data['revision'])) ? $data['revision'] : null;
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
        if(count($dimension) != 2)
        {
            $dimX=0;
            $dimY=0;
        }
        else
        {
            $dimX = intval($dimension[0]);
            $dimY = intval($dimension[1]);
        }
        $path = $this->getImageBasepath(true)."/".$this->id;
		if($dimX != 0 || $dimY!=0) //Original Image needed
		{
			
            $path .= "_".$dimX."_".$dimY;
        }
        return $path;
    }
    
    public function setServiceManager(ServiceManager $sm)
    {
        $this->serviceManager=$sm;
    }
    
    private function getImageBasepath($original = true)
    {
        if(!$this->serviceManager) throw new Exception("Service Manager not defined");
        $options = $this->serviceManager->get('config');
        if(!$this->id) throw new \Exception("Could not generate Imagepath");
        $md5 = md5($this->id);
        $base = $options['cis']['core']['imgpath'][$original?'original':'resized'].substr(chunk_split($md5, 2, '/'), 0, -1); 
        //echo $base;exit();
        return $base;
    }
    
}

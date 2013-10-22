<?php
namespace Ciscore\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class ImageTable extends AbstractTableGateway implements AdapterAwareInterface, ServiceManagerAwareInterface
{
    /*
     * @var ServiceManager
     */
    protected $serviceManager=null;
    
    protected $table='image';

    public function __construct()//(Adapter $adapter)
    {
        //$this->adapter = $adapter;
        //$this->initialize();
    }
    
    public function initialize()
    {
        $this->resultSetPrototype = new ResultSet();
        $img = new Image();
        if($this->serviceManager)
        {
            $img->setServiceManager($this->serviceManager);
        }
        $this->resultSetPrototype->setArrayObjectPrototype($img);
        if($this->adapter)
        {
            parent::initialize();
        }
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }
    public function setServiceManager(ServiceManager $sm)
    {
        $this->serviceManager=$sm;
        $this->initialize();
    }
    
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getImage($id)
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }
        
        $id  = (int) $id;
        

        $select = $this->sql->select();
        $select->where(array('id' => $id))->order('revision desc')->limit(1);

        $rowset = $this->selectWith($select);
        //$rowset = $this->select(array('id' => $id))->orderby('revision desc')->limit(1);
        $row = $rowset->current();
        if (!$row) {
            //throw new \Exception("Could not find row $id");
            return false;
        }
        return $row;
    }

    public function saveImage(Image $image)
    {
        $data = array(
            'title' => $image->title,
            'type'  => $image->type,
            'filename'  => $image->filename,
            'credit'  => $image->credit,
        );

        $id = (int)$image->id;
        if ($id == 0) {
            if(!$image->hasTmpFile())
            {
                throw new Exception("Saving new imageset without image, impossible");
            }
            $image->revision=0;
            $data['type']=$image->getTmpImagetype();
            $this->insert($data);
            $image->id = $this->lastInsertValue;
            if($saveResult!==true)
            {
                throw new \Exception('Image could not be saved('.$saveResult.')');
            }
        } else {
            $orig= $this->getImage($id);
            if ($orig) {
                $image->revision=$data['revision']=$orig->revision+1;
                if($image->hasTmpFile())
                {
                    $saveResult=$image->saveTmpFile();
                    if($saveResult!==true)
                    {
                        throw new \Exception('Image could not be saved('.$saveResult.')');
                    }
                    $data['type']=$image->getTmpImagetype();
                }
                else
                {
                    link($orig->getImagePath(),$image->getImagePath());
                }
                $data['id']=$id;
                $this->insert($data);
            } else {
                throw new \Exception('Imageid does not exist');
            }
        }
    }

    public function deleteImage($id)
    {
        $this->delete(array('id' => $id));
    }
}

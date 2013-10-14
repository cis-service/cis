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
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
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
            $this->insert($data);
        } else {
            if ($this->getImage($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteImage($id)
    {
        $this->delete(array('id' => $id));
    }
}

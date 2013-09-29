<?php
/**
 * Central Image Service API
 *
 * @link      https://github.com/cis-service/cis
 */

namespace Ciscore\Model;

class Image
{
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
}

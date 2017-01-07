<?php 

namespace App\Models;

class Image
{
    public $id;
    public $name;
    public $url;	
    public $width;
    public $height;
    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) $this->id = $data['id'];            
            
            $this->name = $data['name'];
            $this->url = $data['url'];
			$this->width = $data['width'];
            $this->height = $data['height'];
        }
    }
}
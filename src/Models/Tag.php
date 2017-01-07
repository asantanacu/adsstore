<?php 

namespace App\Models;

class Tag
{
    public $id;
    public $tag;	
    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) $this->id = $data['id'];            
            
            $this->tag = $data['tag'];
        }
    }
}
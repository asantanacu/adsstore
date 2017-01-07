<?php

namespace App;

Class Application extends Container{
      
    public function escape($text){
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public function app($key, $value = null){
        if(null === $value){
            return $this->offsetGet($key); 
        }
        $this->offsetSet($key, $value); 
    }

    public function view($block, array $variables = []){
        $this['view']->view($block, $variables);
    }

}

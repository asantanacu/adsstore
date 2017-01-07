<?php

namespace App;

use App\Http\Request;
use App\Http\Response;
use App\Services\Config;

class Container implements \ArrayAccess{
    protected $container = array();
    
    public function __construct($conf_file){
        $app = $this;

        $this['app'] = $this;

        $this['config'] = function() use($conf_file){
            return new Config($conf_file);
        };

        foreach($this['config']['services'] as $service_name => $service)
        {
            $this[$service_name] = function() use($app, $service){
                $service_class = $service[0];
                $parameters = [];
                foreach($service[1] as $parameter){
                    $parameters[] = $app[$parameter];
                };

                $r = new \ReflectionClass($service_class); 
                return $r->newInstanceArgs($parameters); 
            };

        }

        $this['request'] = function() { 
            return new Request($_GET, $_POST, $_FILES, $_SERVER, $_COOKIE);
        };
		
        $this['response'] = function() {
           return new Response();
        };

    }
    
    public function offsetGet($offset){
        if(array_key_exists($offset, $this->container) && 
            is_callable($this->container[$offset])){
            $this->container[$offset] = $this->container[$offset]();
        }
        return $this->container[$offset];
    }
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
}
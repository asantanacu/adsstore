<?php
namespace App\Http;

class Kernel extends \App\Kernel
{
    /**
    * Run the HTTP application
    *
    * @return void
    */    
    public function run(){
        //get the resource form route service
        $resource = $this->app['route']->match($_SERVER, $_POST); 

        if(is_array($resource)){
            if(isset($resource['handler']))
                call_user_func_array($resource['handler'], [$resource['args'], $this->app]);
            else
                $this->controllerDispatcher($resource);
        }
        else
            throw new \Exception("route not match"); 
    }

    /**
    * Dispatch the controller
    *
    * @param  array  $resource  
    * @return void
    */ 
    private function controllerDispatcher(array $resource){
        $controller = "App\Controllers\\".$resource['controller']; 
        $method     = $resource['method']; 
        $args       = $resource['args'];

        if(isset($resource['middleware']))
            if(call_user_func($resource['middleware'], $this->app) == false)
                return;
        
        if(!class_exists($controller)){
            throw new \Exception("controller $controller does not exist");
        }
        $controller = new $controller; 
        if(!method_exists($controller, $method)){
            throw new \Exception("method $method does not exist in $controller"); 
        }

        $controller->$method($args, $this->app);
    }
}
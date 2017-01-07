<?php

namespace App\Services;

class RouteManager{
    
    public $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'DELETE' => [],   
    ];
    public $patterns = [
        ':any'  => '.*',
        ':id'   => '[0-9]+',
        ':slug' => '[a-z-0-9\-]+',
        ':name' => '[a-zA-Z]+',
    ];
    
    const REGVAL = '/({:.+?})/';    
   
    public function __construct($config){
        if(!isset($config['route'])){
            throw new \InvalidArgumentException(
                'RouteManager class requires route configuration'
            );
        }
		
		if(file_exists($config['route']['path'])){
                $router = $this;    
                require $config['route']['path']; 
        }		
    }   
   
    public function any($path, $handler, $middleware = null){
        $this->addRoute('GET', $path, $handler, $middleware);
        $this->addRoute('POST', $path, $handler, $middleware);
        $this->addRoute('PUT', $path, $handler, $middleware);
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    public function resource($path, $controller, $middleware = null){
        $this->addRoute('GET', $path, $controller."@index", $middleware);
        $this->addRoute('GET', $path . '/create', $controller."@create", $middleware);
        $this->addRoute('POST', $path, $controller."@store", $middleware);
        $this->addRoute('GET', $path . '/{:id}', $controller."@show", $middleware);
        $this->addRoute('GET', $path . '/{:id}/edit', $controller."@edit", $middleware);
        $this->addRoute('PUT', $path . '/{:id}', $controller."@update", $middleware);
        $this->addRoute('DELETE', $path .'/{:id}', $controller."@destroy", $middleware);
    }    
    public function get($path, $handler, $middleware = null){
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post($path, $handler, $middleware = null){
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    public function put($path, $handler, $middleware = null){
        $this->addRoute('PUT', $path, $handler, $middleware);
    }
    public function delete($path, $handler, $middleware = null){
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    protected function addRoute($method, $path, $handler, $middleware = null){
        array_push($this->routes[$method], [$path => $handler, 'middleware'=>$middleware]);
    }
    public function match(array $server = [], array $post){
        $requestMethod = $server['REQUEST_METHOD'];
        $requestUri    = strtok($server['REQUEST_URI'],'?');
        $restMethod = $this->getRestfullMethod($post); 
      
        if (null === $restMethod && !in_array($requestMethod, array_keys($this->routes))) {
            return false;
        }
        
        $method = $restMethod ?: $requestMethod;
        foreach ($this->routes[$method]  as $resource) {
            $args    = []; 
            $route   = key($resource); 
            $handler = reset($resource);
            $middleware = $resource['middleware'];
            if(preg_match(self::REGVAL, $route)){
                list($args, ,$route) = $this->parseRegexRoute($requestUri, $route);  
            }
            if(!preg_match("#^$route$#", $requestUri)){
                unset($this->routes[$method]);
                continue ;
            }
            if(is_string($handler) && strpos($handler, '@')){
                list($ctrl, $method) = explode('@', $handler); 
                return ['controller' => $ctrl, 'method' => $method, 'args' => $args, 'middleware'=>$middleware];
            }
			else
				return	['handler' => $handler, 'args' => $args, 'middleware'=>$middleware];
          }
     }
   
    protected function getRestfullMethod($postVar){
        if(array_key_exists('_method', $postVar)){
        	$method = strtoupper($postVar['_method']);
            if(in_array($method, array_keys($this->routes))){
                return $method;
            }
        }
    } 
    protected function parseRegexRoute($requestUri, $resource){
        $route = preg_replace_callback(self::REGVAL, function($matches) {
            $patterns = $this->patterns; 
            $matches[0] = str_replace(['{', '}'], '', $matches[0]);
            
            if(in_array($matches[0], array_keys($patterns))){                       
                return  $patterns[$matches[0]];
            }
        }, $resource);
       
        $args = array_diff_assoc(explode('/', $requestUri) ,explode('/', $resource));  
        return [array_values($args), $resource, $route]; 
    }
}
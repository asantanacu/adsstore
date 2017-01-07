<?php

namespace App\Services;

class Session{
    public function __construct(){
        if(!session_id())
			$this->start();
    }
    public function set($key, $value){
        $_SESSION[$key] = $value;
    }
    public function get($key){
        if(array_key_exists($key, $_SESSION)){
            return $_SESSION[$key]; 
        }
        return FALSE;
    }
    public function delete($key){
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }
    public function regenerate($delete_old_session = false){
        session_regenerate_id($delete_old_session);
        return session_id();
    }
    public function start(){
        session_start();
    }
    public function destroy(){
    	session_destroy(); 
    }
    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }
    public function clear()
    {
        $_SESSION = array();
    }
}
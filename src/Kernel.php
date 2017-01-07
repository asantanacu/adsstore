<?php
namespace App;

class Kernel
{
	public $app;

    public function __construct($app){
    	$this->app = $app;
    }
}
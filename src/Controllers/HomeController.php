<?php

namespace App\Controllers;

use App\Application;

class HomeController
{

    /**
    * Displays content based on whether or not there is an authenticated user.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */
    public function index(array $args, Application $app)
    {
        if($app['session']->exists('user')){
            $app['response']->redirect('/image');
            $app['response']->render();
        }
        else
        	$app['view']->view('search');
    }

}

?>
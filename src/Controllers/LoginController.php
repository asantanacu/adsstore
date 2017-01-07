<?php

namespace App\Controllers;

use App\Application;
use App\Models\User;
use App\Repository\UserRepository;

class LoginController
{
    /**
    * Display a login form.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function get(array $args, Application $app)
    {
        $app['view']->view('login');
    }
	
    /**
    * Receive and validate credentials.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function post(array $args, Application $app)
    {
        //get request and response
        $request = $app['request'];
        $response = $app['response'];

        //try to authenticate a user
        $user = $app['user_repository']->auth($request->post('email'),md5($request->post('password')));
        if($user){
        	//set authenticated user in session
            $app['session']->set('user', ['email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname]);
            $app['session']->set('message', 'Login successfully!');
            $response->redirect('/');
        }
        else{
            $app['session']->set('message', 'Invalid credentials!');
            $app['response']->redirect('/login');	
        }

        $app['response']->render();
    }

    /**
    * Logout user.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function logout(array $args, Application $app)
    {
        //clear session
        $app['session']->clear();

        // message and redirect
        $app['session']->set('message', 'Logout successfully!');
        $app['response']->redirect('/');
        $app['response']->render();
    }		

}

?>
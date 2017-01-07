<?php

namespace App\Controllers;

use App\Application;
use App\Models\User;
use App\Repository\UserRepository;

class RegisterController
{
    /**
    * Display a register form.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function get(array $args, Application $app)
    {
        $app['view']->view('register');
    }
	
    /**
    * Register a user.
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

        //create new user
        $user = new User([
            'email'=>$request->post('email'),
            'firstname'=>$request->post('firstname'),
            'lastname'=>$request->post('lastname'),
            'birth_date'=>$request->post('birth_date'),
            'password'=>md5($request->post('password'))
        ]);

        //save new user
        $app['user_repository']->save($user);
        //set as authenticated user in session
        $app['session']->set('user', ['email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname]);

        // message and redirect
        $app['session']->set('message', 'Successfully registered!');
        $response->redirect('/');
        $response->render();
    }	

}

?>
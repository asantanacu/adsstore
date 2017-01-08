<?php

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
*/
$router->get('/', 'HomeController@index');
	
$router->get('/login', 'LoginController@get');
$router->post('/login', 'LoginController@post');
$router->get('/logout', 'LoginController@logout');

$router->get('/register', 'RegisterController@get');
$router->post('/register', 'RegisterController@post');

$router->resource('/image', 'ImageController', function(\App\Application $app){
    if(!$app['session']->exists('user')){
        $app['response']->redirect('/');
        $app['response']->render();
        return false;
    }
    return true;
});

	
$router->get('/search', 'SearchController@index');

//only to show how works the route with anonymous functions 
$router->get('/api/search', function(array $args, \App\Application  $app){
       
    $request = $app['request'];
    $match_all = $request->get('match_all') ? true : false;
    //get images
    $images = $app['image_repository']->search($request->get('keywords'), $request->get('width'), $request->get('height'), $request->get('amount'), $match_all);
    //return json
    echo json_encode([
        'count'=>count($images),
        'images'=>array_map(function($image){return ['url'=>$image->url,'width'=>$image->width,'height'=>$image->height];},$images)
    ]);
});
	
?>
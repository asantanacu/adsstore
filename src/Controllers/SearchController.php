<?php

namespace App\Controllers;

use App\Application;

class SearchController
{
    /**
    * Search images by tags
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function index(array $args, Application $app)
    {
        //get request
        $request = $app['request'];
        //get parameter match_all
        $match_all = $request->get('match_all') ? true : false;
        //search images
        $images = $app['image_repository']->search($request->get('tags'), $request->get('width'), $request->get('height'), $request->get('amount'), $match_all);

        // load the view and pass the images
        $app['view']->view('search', ['images'=>$images]);
    }		

}

?>
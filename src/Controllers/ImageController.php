<?php

namespace App\Controllers;

use App\Application;
use App\Models\Image;
use App\Repository\ImageRepository;

class ImageController
{
    /**
    * Display a listing of the resource.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function index(array $args, Application $app)
    {
        // get all the images
        $images = $app['image_repository']->findAll();
        // load the view and pass the images
        $app['view']->view('images/index', ['images'=> $images]);
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function create(array $args, Application $app)
    {
        $app['view']->view('images/edit');
    }

    /**
    * Store a newly created resource in database.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function store(array $args, Application $app)
    {
        //get request and response
        $request = $app['request'];
        $response = $app['response'];

        //create images from input request
        $image = new Image(
            ['name'=>$request->post('name'),
            'url'=>$request->post('url'),
            'width'=>$request->post('width'),
            'height'=>$request->post('height')]
        );

        //save image
        $image = $app['image_repository']->save($image);
        //search the tags ids, creating the no existing ones
        $tags = $app['tag_repository']->searchByTags($request->post('tags'));
        //udpate tags ids in the new image
        $app['image_repository']->updateTags($image->id, $tags);

        // message and redirect
        $app['session']->set('message', 'Image created successfully!');
        $response->redirect('/image');
        $response->render();              
    }
    
    /**
    * Display the specified resource.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function show(array $args, Application $app)
    {
        // find the image
        $image = $app['image_repository']->find($args[0]);
        // get tags for the image
        $image->tags = $app['image_repository']->getTags($args[0]);
        // show the view and pass the image to it
        $app['view']->view('images/show', ['image'=> $image]);    	
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function edit(array $args, Application $app)
    {
        // get all the images
        $image = $app['image_repository']->find($args[0]);
        // get tags for the image
        $image->tags = $app['image_repository']->getTags($args[0]);     
        // show the edit form and pass the image
        $app['view']->view('images/edit', ['image'=> $image]);     	
    }

    /**
    * Update the specified resource in database.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function update(array $args, Application $app)
    {
        //get request and response
        $request = $app['request'];
        $response = $app['response'];
		
        //create images from input request
        $image = new Image(
            ['name'=>$request->post('name'),
            'url'=>$request->post('url'),
            'width'=>$request->post('width'),
            'height'=>$request->post('height')]
        );

        //get id from parameters
		$image->id = $args[0];
        //save image
        $image = $app['image_repository']->update($image);
        //search the tags ids, creating the no existing ones
        $tags = $app['tag_repository']->searchByTags($request->post('tags'));
        //udpate tags ids in the new image
        $app['image_repository']->updateTags($image->id, $tags);

        // message and redirect
        $app['session']->set('message', 'Image updated successfully!');      
		$response->redirect('/image');
		$response->render();     	
    }

    /**
    * Remove the specified resource from database.
    *
    * @param  array  $args    
    * @param  Application  $app
    * @return void
    */    
    public function destroy(array $args, Application $app)
    {
        // delete image
        $app['image_repository']->delete($args[0]);

        // message and redirect
        $app['session']->set('message', 'Image deleted successfully!');
        $response = $app['response'];
        $response->redirect('/image');
        $response->render();        
    }
}
<?php
namespace App\Console;

use App\Application;

class SearchCommand
{
    /**
    * Find and run the properly command.
    * Fill out the parameters array.
    *
    * @param  Application  $app
    * @param  array  $parameters
    * @return void
    */
    public static function run(Application $app, array $parameters)
    {
        //extract parameters
        $amount = isset($parameters['--amount']) ? $parameters['--amount'] : 0;
        $width = isset($parameters['--width']) ? $parameters['--width'] : 0;
        $height = isset($parameters['--height']) ? $parameters['--height'] : 0;
        $match_all = array_key_exists('--match_all',$parameters) ? true : false;

        //search in image repository
        $images = $app['image_repository']->search($parameters['--tags'], $width, $height, $amount, $match_all);

        //print results in the required format
        if(array_key_exists('--pretty',$parameters))
            SELF::print_pretty($images);
        else
            echo json_encode([
                'count'=>count($images),
                'images'=>array_map(function($image){return ['url'=>$image->url,'width'=>$image->width,'height'=>$image->height];},$images)
            ]);
    }

    /**
    * Print human readable format
    *
    * @param  array  $images
    * @return void
    */
    private static function print_pretty(array $images)
    {
        echo PHP_EOL;
        echo 'Count Images: '.count($images);
        echo PHP_EOL;
        foreach($images as $image)
        {
            echo '   Image:';
            echo PHP_EOL;
            echo '     URL: '.$image->url;
            echo PHP_EOL;
            echo '     Image Width: '.$image->width;
            echo PHP_EOL;
            echo '     Image Height: '.$image->height;
            echo PHP_EOL;
        }
    }
}
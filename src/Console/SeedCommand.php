<?php
namespace App\Console;

use App\Application;
use App\Models\User;
use App\Models\Image;
use App\Models\Keyword;

class SeedCommand
{
    /**
    * Seed database
    *
    * @param  Application  $app
    * @param  array  $parameters
    * @return void
    */
    public static function run(Application $app, array $parameters)
    {
        echo "Running command 'Seed'...".PHP_EOL;
        
        if(isset($parameters['--user']))
            SELF::seedUser($app, explode(',', $parameters['--user']));

        if(isset($parameters['--keywords']))
            SELF::seedKeywords($app, explode(',', $parameters['--keywords']));

        if(isset($parameters['--image']))
            SELF::seedImage($app, explode(',', $parameters['--image'],5));

        if(array_key_exists('--fake', $parameters)){
            SELF::seedFake($app, $parameters);        
        }

        echo "Finished successfully.".PHP_EOL;
    }

    protected static function seedUser(Application $app, array $parameters)
    {
        //create user
        $user = new User([
            'email'=>$parameters[0],
            'firstname'=>$parameters[1],
            'lastname'=>$parameters[2],
            'birth_date'=>$parameters[3],
            'password'=>md5($parameters[4])
        ]);

        //save user
        $app['user_repository']->save($user);
    }

    protected static function seedKeywords(Application $app, array $keywords)
    {
        //create keywords
        foreach($keywords as $keyword)
        {
            $keyword = new Keyword(['name'=>$keyword]);
            $app['keyword_repository']->save($keyword);
        }
    }

    protected static function seedImage(Application $app, array $parameters)
    {
        //create image
        $image = new Image([
            'name'=>$parameters[0],
            'url'=>$parameters[1],
            'width'=>$parameters[2],
            'height'=>$parameters[3],
        ]);

        //save image
        $image = $app['image_repository']->save($image);
        if(isset($parameters[4])){
            //search the keywords ids, creating the no existing ones
            $keywords = $app['keyword_repository']->searchByKeywords($parameters[4]);
            //udpate keywords ids in the new image
            $app['image_repository']->updateKeywords($image->id, $keywords);        
        }
    }

    protected static function seedFake(Application $app, array $parameters)
    {
        $colors = ['red','blue','green','white','black','yellow','orange','pink','purple','gray'];
        $sizes = [400,450,500,550,600,650,700,750,800,850,900];
        $states = [];
        $words = [];
        $faker = \Faker\Factory::create();

        //create user
        $user = new User([
            'email'=>'info@adsstore.com',
            'firstname'=>$faker->firstName,
            'lastname'=>$faker->lastName,
            'birth_date'=>'1980-1-1',
            'password'=>md5('secret')
        ]);

        //save user
        $app['user_repository']->save($user);

        for($i = 0; $i <10; $i++)
        {
            $words[] = $faker->word;
            $words[] = $faker->creditCardType;
            $states[] = $faker->state;
            $states[] = $faker->state;
        }

        for($i = 0; $i < 100; $i++)
        {
            $width = $faker->randomElement($sizes);
            $height = $faker->randomElement($sizes);
            //create image
            $image = new Image([
                'name'=>$faker->sentence(3),
                'url'=>$faker->imageUrl($width,$height),
                'width'=>$width,
                'height'=>$height,
            ]);

            //save image
            $image = $app['image_repository']->save($image);
            $keywords = $faker->randomElement($colors).','.$faker->randomElement($words).','.$faker->randomElement($words).','.$faker->randomElement($states);
            //search the keywords ids, creating the no existing ones
            $keywords = $app['keyword_repository']->searchByKeywords($keywords);
            //udpate keywords ids in the new image
            $app['image_repository']->updateKeywords($image->id, $keywords);        
        }
    }  

}
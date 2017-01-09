<?php
namespace App\Console;

/**
* Manage the console commands.
**/
class Kernel extends \App\Kernel
{
    /**
    * Find and run the properly command.
    * Fill out the parameters array.
    *
    * @param  array  $argv
    * @return void
    */
    public function run(array $argv)
    {
        try{
            $command = 'App\\Console\\'.ucfirst($argv[1]).'Command';

            $parameters = array();
            foreach ($argv as $arg){
                $key;
                $value;
                list($key,$value) = array_pad(explode('=', $arg,2), 2, null);
                if($key)
                    $parameters[$key]=$value;
            }

            echo "Running command '$command'...".PHP_EOL;

            call_user_func([$command,'run'],$this->app, $parameters);

            echo "Finished successfully.".PHP_EOL;
        }
        catch(\Exception $e){
            if($this->app->environment('PRODUCTION'))
            {
                echo "Unexpected error.".PHP_EOL;
                echo $e->getMessage().PHP_EOL;
            }
            else
                throw $e;
        }
    }
}
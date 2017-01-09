<?php
namespace App\Console;

use App\Application;

class DatabaseCommand
{
    /**
    * Run database scripts
    *
    * @param  Application  $app
    * @param  array  $parameters
    * @return void
    */
    public static function run(Application $app, array $parameters)
    {
        echo "Running command 'database'...".PHP_EOL;
        //extract parameters
        if(!isset($parameters['--file'])){
            echo "Missing parameter --file".PHP_EOL;
            return;
        }

        $file =  __DIR__ . "/../../".$parameters['--file'];
        if(!file_exists($file)){
            echo "File '$file' does not exists".PHP_EOL;
            return;
        }
        
        echo "Opening file '$file' ...".PHP_EOL;

        $sql = file_get_contents($file);
        $sql = "USE {$app['config']['database']['database']}; ".$sql;
        $stmt = $app['database']->getConnection()->prepare($sql, array( \PDO::ATTR_EMULATE_PREPARES => true ) );
        $stmt->execute();   

        echo "Finished successfully.".PHP_EOL;
    }

}
# The Ads Store  
Advertising Store created from scratch  

#dependency  
PHP >= 5.6  

#install  
Clone in a PHP server and point the host to public folder (../adsstore/public).  
Run composer, only to use the autoload for namespaces and only the external package "fzaninotto/faker".    

#setup  
Open config/conf.php and enter the database configuration  
$config['database'] = [  
    'host'       => 'localhost',  
    'port'       => '3306',  
    'database'   => 'ads_store',  
    'username'   => 'homestead',  
    'password'   => 'secret'  
];  

#import database schema
In the main folder of the project "adsstore", run follow command
php adsstore database --file=database.sql

#seed with fake data
In the main folder of the project "adsstore", run follow command
php adsstore seed --fake

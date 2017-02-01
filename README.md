# The Ads Store

Advertising Store created from scratch

## dependency

PHP >= 5.6

## 1. install

Clone in a PHP server and point the host to public folder (../adsstore/public).  
Go to the main folder of the project "adsstore" and run composer, only to use the autoload for namespaces and the external package "fzaninotto/faker".

`composer install`

## 2. setup

Open config/conf.php and enter the database configuration   
$config['database'] = [  
'host' => 'localhost',  
'port' => '3306',  
'database' => 'ads_store',  
'username' => 'homestead',  
'password' => 'secret'  
];  

## 3. import database schema

Go to the main folder of the project "adsstore" and run follow command

`php adsstore database --file=database.sql`

## 4. seed with fake data

Go to the main folder of the project "adsstore" and run follow command

`php adsstore seed --fake`

## 5. commands available

### 5.1 search

command search allow ask for a number of images filter by keywords, width and heigh

ex. `php adsstore search --keywords="red,visa" --amount=5 --width=700 --height=600 --match_all --pretty`

"--keywords" => required, comma-seppareted-value keywords to search  
"--match_all" => optional, is to indicate whether we want one or all the keywords matched  
"--amount" => optional, the maximum of images to return  
"--width" => optional, return only images with the indicated width  
"--height" => optional, return only images with the indicated heigth  
"--pretty" => optional, make the response human readable  

### 5.2 database

command to run script file in the database

ex. `php adsstore database --file="file.sql"`

"--file" => required, file with sql statements

### 5.3 seed

command to insert data in the database

ex `php adsstore seed --fake`  
ex `php adsstore seed --user="info@adsstore.com,John,Doe,1980-1-1,secret"`  
ex `php adsstore seed --keywords="red,blue,green"`  
ex `php adsstore seed --image="flowers,http://images/flowers.jpg,500,600,red,country,flowers"`  

"--fake" => optional, insert one user in the database, insert 100 images with aleatories sizes and keywords  
"--user" => optional, insert a user in the database, fields of the user should be separated by comma [email,firstname,lastname,birthday,password]  
"--keywords" => optional, insert a list of keywords in the database, keywords should be separeted by comma  
"--image" => optional, insert an image in the database, fields of the user should be separeted by comma [name,url,width,height,keyword1,keyword2,keyword3...]  

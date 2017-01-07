<?php

namespace App\Services;

class Config extends Service {

    public function __construct($conf_file) {
        if(file_exists($conf_file)){
            $config = $this;
            require $conf_file;
        }
        else
            throw new \InvalidArgumentException(
                'config class requires a configuration file'
            );
    }
}

?>
<?php

// routes file
$config['route'] = [
    'path' => __DIR__ . '/routes.php'
];

// views folder
$config['view'] = [
    'path'  => __DIR__ . '/../views/',
];

// database details 
$config['database'] = [
    'host'       => 'localhost',
    'database'   => 'ads_store',
    'username'   => 'homestead',
    'password'   => 'secret',
    'port'       => '3306'
];

// dependency injection
$config['services'] = [
    'console_kernel' => [\App\Console\Kernel::class,
        ['app']
    ],
    'http_kernel' => [\App\Http\Kernel::class,
        ['app']
    ],
    'route' => [\App\Services\RouteManager::class, 
        ['config']
    ],
    'view' => [\App\Services\Template::class, 
        ['app','config']
    ],
    'session' => [\App\Services\Session::class, 
        []
    ],
    'database' => [\App\Services\Database::class, 
        ['config']
    ],
    'user_repository' => [\App\Repository\UserRepository::class,
        ['database']
    ],
    'image_repository' => [\App\Repository\ImageRepository::class,
        ['database']
    ],
    'tag_repository' => [\App\Repository\TagRepository::class,
        ['database']
    ]	
];







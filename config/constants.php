<?php 

return [
    'cache_expiry' => 86400, // in seconds (60*60*24 = 1 day) 
    'site_timezone' => 'CDT', // By default CDT timezone wise date will display 
    'storage_path' => '/app/public/',
    'upload_path' => 'public/',
    'link_path' => 'public/storage/',
    'status' => [
        'active' => 1,
        'deactive' => 0,
    ],
    'user_type' => [
        'back_office' => 0,
        'engineer' => 1,
        'client' => 2,
        'vendor' => 3
    ]
];
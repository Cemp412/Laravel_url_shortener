<?php 

return [

    'app' => [
        'name' => env('APP_NAME', 'Sembark URL Shortener'),
        'url'  =>  env('APP_URL', 'http://localhost'),
    ],

    'auth' => [
        'pass_key' => env('PASS_KEY', 'secret$String')
    ]
]
// config('constants.app.name'); //use in view
?>
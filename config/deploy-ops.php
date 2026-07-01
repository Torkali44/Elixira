<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Deploy ops token (no server terminal)
    |--------------------------------------------------------------------------
    |
    | Set DEPLOY_OPS_TOKEN in .env, then open:
    | https://yourdomain.com/ops/run/{token}
    |
    | Add or remove Artisan commands below whenever you deploy changes.
    |
    */
    'token' => env('DEPLOY_OPS_TOKEN'),

    'commands' => [
        'optimize:clear',
        'migrate --force',
        'config:clear',
        'cache:clear',
        'view:clear',
        'route:clear',
        // 'migrate --force',
        // 'storage:publish --force',
    ],

];

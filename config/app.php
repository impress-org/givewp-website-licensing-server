<?php

use App\Upgrades\Version0p1p0;

return [
    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value is the version of the application. This is used when the
    | framework needs to run upgrades or provide a notification.
    |
    */
    'version' => '0.1.0',

    /*
   |--------------------------------------------------------------------------
   | Autoloaded Service Providers
   |--------------------------------------------------------------------------
   |
   | The service providers listed here will be automatically loaded on the
   | request to your application. Feel free to add your own services to
   | this array to grant expanded functionality to your applications.
   |
   */

    'providers' => [

        /*
       * Application Service Providers...
       */
        App\Providers\AppServiceProvider::class,
    ],

    /*
   |--------------------------------------------------------------------------
   | App Upgrade Services
   |--------------------------------------------------------------------------
   |
   | The services which provide upgrade operations at specific versions.
   |
   */

    'upgrades' => [
        '0.1.0' => Version0p1p0::class
    ],
];

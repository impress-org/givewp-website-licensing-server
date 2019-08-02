<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/* @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('edd-sl-api', 'LicenseController@handle');

$router->post('auth/login', array('uses' => 'AuthController@authenticate'));

$router->group(
    array(
        'middleware' => 'jwt.auth',
        'prefix'     => 'update',
    ),
    function () use ($router) {
        $router->post('license', 'StoreDataController@handle' );
        $router->post('subscription', 'StoreDataController@handle' );
        $router->post('addon', 'StoreDataController@handle' );
    }
);

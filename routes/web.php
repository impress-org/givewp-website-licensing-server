<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

/* @var Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});


/*
|--------------------------------------------------------------------------
| Public Rest API Endpoints
|--------------------------------------------------------------------------
|
*/
$router->post('edd-sl-api', 'LicenseController@handle');

$router->post('auth/login', array('uses' => 'AuthController@authenticate'));

$router->group(
    array(
        'middleware' => 'jwt.auth',
        'prefix'     => 'update',
    ),
    function () use ($router) {
        $router->post('license', 'UpdateDataController@handle');
        $router->post('subscription', 'UpdateDataController@handle');
        $router->post('addon', 'UpdateDataController@handle');
    }
);

/*
|--------------------------------------------------------------------------
| Artisan Routes
|--------------------------------------------------------------------------
|
| These are the routes intended for exposing artisan commands as endpoints
| for the purpose of running commands on App Engine.
|
| All routes are in the Artisan namespace and
| All endpoints begin with /artisan/ prefixed
*/

if (! App::environment('production')) {
    // Dangerous endpoint, do not even add routes on production
    Route::get('/app/fresh', 'ArtisanController@fresh');
    Route::post('/app/fresh', 'ArtisanController@fresh');
}

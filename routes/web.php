<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
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
$router->post('test-redis', function () {
    Cache::increment('testCounter');

    return response(Cache::get('testCounter'));
});

$router->post('test-redis/{key}/{$value}', static function (string $key, string $value) {
    Cache::put($key, $value, 60);

    return response(Cache::get($key));
});

$router->get('test-redis/{key}', static function (string $key) {
    return response(Cache::get($key));
});

$router->post('edd-sl-api', 'LicenseController@handle');

$router->post('auth/login', ['uses' => 'AuthController@authenticate']);

$router->group(
    [
        'middleware' => 'jwt.auth',
        'prefix'     => 'update',
    ],
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

Route::get('/app/update', 'ArtisanController@update');

if (! App::environment('production')) {
    // Dangerous endpoint, do not even add routes on production
    Route::get('/app/fresh', 'ArtisanController@fresh');
    Route::post('/app/fresh', 'ArtisanController@fresh');
}

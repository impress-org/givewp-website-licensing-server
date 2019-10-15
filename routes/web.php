<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
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
$router->post('redis/increment', function () {
    Redis::incr('redisCounter');

    return response(Redis::get('redisCounter'));
});

$router->post('redis/set/{key}/{value}', function (string $key, string $value) {
    Redis::set($key, $value);

    return response(Redis::get($key));
});

$router->get('redis/get/{key}', function (string $key) {
    return response(Redis::get($key));
});

$router->post('cache/increment', function () {
    Cache::increment('cacheCounter');

    return response(Cache::get('cacheCounter'));
});

$router->post('cache/set/{key}/{value}', function (string $key, string $value) {
    Cache::set($key, $value, 60);

    return response(Cache::get($key));
});

$router->get('cache/get/{key}', function (string $key) {
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

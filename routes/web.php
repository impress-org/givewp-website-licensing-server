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


/*
|--------------------------------------------------------------------------
| Redis & Cache Routes
|--------------------------------------------------------------------------
|
| These are the routes intended for testing cache and redis commands on the
| staging environment. They should NEVER be made to work on production.
|
| All cache routes begin with /cache/
| All redis routes begin with /redis/
*/

if (! App::environment('production')) {
    $router->delete('cache', function () {
        CACHE::flush();

        return response('All cache cleared, including redis data.');
    });

    $router->post('redis/{key}/{value}', function (string $key, string $value) {
        Redis::set($key, $value);

        return response(Redis::get($key));
    });

    $router->put('redis/{key}', function (string $key) {
        Redis::incr($key);

        return response(Redis::get($key));
    });

    $router->get('redis/{key}', function (string $key) {
        return response(Redis::get($key));
    });

    $router->delete('redis/{key}', function (string $key) {
        Redis::del($key);

        return response("Deleted the redis $key key");
    });

    $router->post('cache/{key}/{value}', function (string $key, string $value) {
        Cache::put($key, $value, 3600);

        return response(Cache::get($key));
    });

    $router->put('cache/{key}', function (string $key) {
        Cache::increment($key);

        return response(Cache::get($key));
    });

    $router->get('cache/{key}', function (string $key) {
        return response(Cache::get($key));
    });

    $router->delete('cache/{key}', function (string $key) {
        Cache::forget($key);

        return response("Deleted the cache $key key");
    });
}

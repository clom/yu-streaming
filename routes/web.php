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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// stream API
$router->get('/api/v1/stream', 'StreamController@getStream');
$router->get('/api/v1/stream/{uuid}', 'StreamController@getStreamByUuid');
$router->post('/api/v1/stream', 'StreamController@createStream');
$router->put('/api/v1/stream/{uuid}', 'StreamController@updateStream');
$router->delete('/api/v1/stream/{uuid}', 'StreamController@deleteStream');

// live API
$router->post('/api/v1/live/start', 'LiveController@postLiveStart');
$router->post('/api/v1/live/stop', 'LiveController@postLiveStop');

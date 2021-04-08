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
    return view('index');
});

$router->get('/vod', function () use ($router) {
    return view('vod');
});

$router->get('/live/{uuid}', function ($uuid) use ($router) {
    return view('player', ['uuid' => $uuid]);
});

$router->get('/vod/{uuid}', function ($uuid) use ($router) {
    return view('player_vod', ['uuid' => $uuid]);
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

// archive API
$router->get('/api/v1/archive', 'ArchiveController@getArchive');
$router->get('/api/v1/archive/{uuid}', 'ArchiveController@getArchiveByUuid');
$router->post('/api/v1/archive/start', 'ArchiveController@postArchiveStart');
$router->post('/api/v1/archive/stop', 'ArchiveController@postArchiveStop');

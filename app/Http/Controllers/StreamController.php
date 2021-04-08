<?php

namespace App\Http\Controllers;

use App\Stream;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Laravel\Lumen\Routing\Controller as BaseController;

class StreamController extends BaseController
{
  public function getStream(Request $request)
  {
    $request = $request->all();

    $condition = [
      'is_delete' => false,
      'is_live' => true
    ];

    if (isset($request['is_all_stream']) && $request['is_all_stream'] == 'true') {
      unset($condition['is_live']);
    }
  
    $streams = Stream::where($condition)
                      ->orderBy('updated_at', 'desc')
                      ->get();

    return response()->json($streams);
  }

  public function getStreamByUuid($uuid, Request $request)
  {
    $request = $request->all();

    $condition = [
      'is_delete' => false,
      'is_live' => true
    ];

    if (isset($request['is_all_stream']) && $request['is_all_stream'] == 'true') {
      unset($condition['is_live']);
    }

    $streams = Stream::where('uuid', $uuid)
                    ->where($condition)
                    ->orderBy('updated_at', 'desc')
                    ->first();

    if (!$streams) {
      return response()->json(['message' => 'not found streams'], 404);
    }

    $visibleCondition = ['playback_url'];

    if (isset($request['is_get_stream_info']) && $request['is_get_stream_info'] == 'true') {
      $visibleCondition = array_merge($visibleCondition, ['rtmp_url', 'stream_key']);
    }


    return response()->json($streams->makeVisible($visibleCondition));
  }

  public function createStream(Request $request)
  {
    $request = $request->json()->all();

    if (empty($request['name'])) {
      return response()->json(['message' => 'empty name'], 400);
    }

    $uuid = Uuid::uuid4();
    $ivs = App::make('aws')->createClient('ivs');
    $result = $ivs->createChannel([
      'latencyMode' => 'LOW',
      'name' => $uuid->toString(),
      'type' => 'STANDARD',
      'recordingConfigurationArn' => env('AWS_RECORDING_CONFIGURATION'),
    ]);

    $stream = Stream::create([
      'uuid' => $uuid->toString(),
      'name' => $request['name'],
      'ivs_arn' => $result['channel']['arn'],
      'playback_url' => $result['channel']['playbackUrl'],
      'rtmp_url' => 'rtmps://' . $result['channel']['ingestEndpoint'] . '/app',
      'stream_key' => $result['streamKey']['value']
    ]);

    return response()->json($stream->makeVisible(['rtmp_url', 'stream_key']), 201);
  }

  public function updateStream($uuid, Request $request)
  {
    $request = $request->json()->all();

    if (empty($request['name'])) {
      return response()->json(['message' => 'empty name'], 400);
    }

    if (empty($uuid)) {
      return response()->json(['message' => 'empty uuid'], 400);
    }

    $condition = [
      'uuid' => $uuid,
      'is_delete' => false
    ];

    $stream = Stream::where($condition)
                    ->orderBy('updated_at', 'desc')
                    ->first();

    if (!$stream) {
      return response()->json(['message' => 'not found streams'], 404);
    }

    $stream->name = $request['name'];
    $stream->save();

    return response()->json($stream, 200);
  }

  public function deleteStream($uuid)
  {
    if (empty($uuid)) {
      return response()->json(['message' => 'empty uuid'], 400);
    }

    $condition = [
      'uuid' => $uuid,
      'is_delete' => false
    ];

    $stream = Stream::where($condition)
                    ->orderBy('updated_at', 'desc')
                    ->first();

    if (!$stream) {
      return response()->json(['message' => 'not found streams'], 404);
    }

    $ivs = App::make('aws')->createClient('ivs');
    $ivs->deleteChannel([
      'arn' => $stream->ivs_arn
    ]);
    
    $stream->is_delete = true;
    $stream->save();

    return response()->json([], 204);
  }
}

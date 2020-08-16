<?php

namespace App\Http\Controllers;

use App\Stream;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LiveController extends BaseController
{
  public function postLiveStart(Request $request)
  {
    $request = $request->json()->all();

    $condition = [
      'ivs_arn' => $request['arn'],
      'is_delete' => false,
      'is_live' => false
    ];

    $stream = Stream::where($condition)
              ->orderBy('updated_at', 'desc')
              ->first();

    if (!$stream) {
      $ivs = App::make('aws')->createClient('ivs');
      $ivs->stopStream([
        'channelArn' => $request['arn']
      ]);
      return response()->json(['message' => 'not found streams'], 404);
    }

    $stream->is_live = true;
    $stream->save();

    return response()->json([]);
  }

  public function postLiveStop(Request $request)
  {
    $request = $request->json()->all();

    $condition = [
      'ivs_arn' => $request['arn'],
      'is_delete' => false,
      'is_live' => true
    ];

    $stream = Stream::where($condition)
              ->orderBy('updated_at', 'desc')
              ->first();

    if (!$stream) {
      return response()->json(['message' => 'not found streams'], 404);
    }

    $stream->is_live = false;
    $stream->save();

    return response()->json([]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Archive;
use App\Stream;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ArchiveController extends BaseController
{
  public function getArchive(Request $request)
  {
    $request = $request->all();

    $condition = [
      'is_delete' => false,
      'is_archive' => true
    ];

    if (isset($request['is_all_archive']) && $request['is_all_archive'] == 'true') {
      unset($condition['is_archive']);
    }
  
    $archive = Archive::where($condition)
                      ->orderBy('updated_at', 'desc')
                      ->get();

    return response()->json($archive);
  }

  public function getArchiveByUuid($uuid, Request $request)
  {
    $request = $request->all();

    $condition = [
      'is_delete' => false,
      'is_archive' => true
    ];

    if (isset($request['is_all_archive']) && $request['is_all_archive'] == 'true') {
      unset($condition['is_archive']);
    }

    $archive = Archive::where('uuid', $uuid)
                    ->where($condition)
                    ->orderBy('updated_at', 'desc')
                    ->first();

    return response()->json($archive);
  }

  public function postArchiveStart(Request $request)
  {
    $request = $request->json()->all();

    $condition = [
      'ivs_stream_id' => $request['stream_id'],
      'is_archive' => false
    ];

    $archive = Archive::where($condition)
              ->orderBy('updated_at', 'desc')
              ->first();

    if ($archive) {
      return response()->json(['message' => 'exists archive'], 404);
    }

    $condition = [
      'ivs_arn' => $request['arn'],
      'is_delete' => false
    ];

    $stream = Stream::where($condition)
              ->orderBy('updated_at', 'desc')
              ->first();

    if (!$stream) {
      return response()->json(['message' => 'not found streams'], 404);
    }

    $uuid = Uuid::uuid4();
    $archive = Archive::create([
      'uuid' => $uuid->toString(),
      'name' => $request['name'],
      'stream_id' => $stream['id'],
      'ivs_stream_id' => $request['stream_id'],
      'playback_url' => env('CDN_URL', 'http://localhost') . $request['playback_path']
    ]);

    return response()->json([]);
  }

  public function postArchiveStop(Request $request)
  {
    $request = $request->json()->all();

    $condition = [
      'ivs_stream_id' => $request['stream_id'],
      'is_archive' => false
    ];

    $archive = Archive::where($condition)
              ->orderBy('updated_at', 'desc')
              ->first();

    if (!$archive) {
      return response()->json(['message' => 'not found archive'], 404);
    }

    $archive->is_archive = true;
    $archive->save();

    return response()->json([]);
  }
}

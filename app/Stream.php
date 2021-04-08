<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{

    protected $table = 'stream';

    protected $guarded  = [
        'id',
    ];

    protected $hidden = [
        'ivs_arn', 'is_delete', 'playback_url', 'rtmp_url', 'stream_key'
    ];
}

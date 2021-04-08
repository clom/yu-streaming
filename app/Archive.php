<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{

    protected $table = 'archive';

    protected $guarded  = [
        'id',
    ];

    protected $hidden = [
    ];
}

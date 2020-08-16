<?php
/**
 * Created by PhpStorm.
 * User: shu920921
 * Date: 2017/06/12
 * Time: 22:22
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{

    protected $table = 'stream';

    protected $guarded  = [
        'id',
    ];

    protected $hidden = [
        'ivs_arn',
    ];
}

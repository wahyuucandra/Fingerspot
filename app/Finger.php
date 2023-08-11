<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finger extends Model
{
    protected $table = 'finger';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;
}

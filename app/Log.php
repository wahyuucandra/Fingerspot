<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $primaryKey = 'log_time ';
    public $incrementing = false;
    public $timestamps = false;
}

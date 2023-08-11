<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    protected $primaryKey = 'sn';
    public $incrementing = false;
    public $timestamps = false;
}

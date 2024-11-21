<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = ['office', 'dni', 'fullname', 'charge', 'ip', 'mac', 'port', 'type', 'is_ugel', 'connection_type', 'use_type'];
}

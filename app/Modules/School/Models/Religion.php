<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    public $timestamps = false;
    protected $fillable = ['religion_name', 'is_active', 'created_at'];
}


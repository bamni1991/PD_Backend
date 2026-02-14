<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class MotherTongue extends Model
{
    public $timestamps = false;
    protected $fillable = ['tongue_name', 'is_active', 'created_at'];
}


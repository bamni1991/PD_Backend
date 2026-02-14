<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    public $timestamps = false;
    protected $fillable = ['nationality_name', 'is_active', 'created_at'];
}

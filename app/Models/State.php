<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;
    protected $fillable = ['state_name', 'country_id', 'is_active', 'created_at'];
}

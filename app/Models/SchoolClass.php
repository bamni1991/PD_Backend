<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    public $timestamps = false;
    protected $fillable = ['class_name', 'created_at'];
}

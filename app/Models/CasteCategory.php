<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasteCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['caste_name', 'category', 'created_at'];
}

<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['item_name', 'price', 'created_at'];
}


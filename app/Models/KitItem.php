<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['item_name', 'price', 'created_at'];
}

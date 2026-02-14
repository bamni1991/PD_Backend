<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    protected $table = 'academic_sessions';
    public $timestamps = false;
    protected $fillable = ['session_name', 'is_active', 'created_at'];
}


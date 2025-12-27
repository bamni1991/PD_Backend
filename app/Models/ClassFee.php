<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassFee extends Model
{
    public $timestamps = false;
    protected $fillable = ['class_id', 'academic_session_id', 'fee_amount', 'created_at'];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }
}

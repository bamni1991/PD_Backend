<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id', 'class_fee_id', 'academic_session_id', 'amount', 'type', 'status', 'paidBy', 'collectedBy', 'mode', 'created_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classFee()
    {
        return $this->belongsTo(ClassFee::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
}

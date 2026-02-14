<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class StudentKit extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id', 'kit_item_id', 'academic_session_id', 'issued_date', 'created_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function kitItem()
    {
        return $this->belongsTo(KitItem::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
}


<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherClass extends Model
{
    use HasFactory;

    protected $table = 'teacher_classes';
    public $timestamps = false; // Schema doesn't have created_at/updated_at for this table

    protected $fillable = [
        'teacher_id',
        'class_id',
        'academic_session_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id', 'id');
    }
}


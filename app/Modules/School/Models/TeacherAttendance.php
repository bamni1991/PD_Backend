<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    use HasFactory;

    protected $table = 'teacher_attendance';

    protected $fillable = [
        'teacher_id',
        'academic_session_id',
        'attendance_date',
        'status',
        'in_time',
        'out_time',
        'in_cordinate',
        'out_cordinate',
        'remarks',
        'marked_by',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
}


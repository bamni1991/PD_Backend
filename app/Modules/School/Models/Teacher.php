<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'qualification',
        'joining_date',
        'experience_years',
        'gender',
        'dob',
        'address',
        'aadhar_number',
        'profile_photo',
        'aadhar_copy',
        'qualification_certificate',
        'academic_session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(TeacherClass::class, 'teacher_id', 'id');
    }
}


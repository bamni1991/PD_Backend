<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at in schema, no updated_at

    protected $fillable = [
        'guardian_id',
        'student_name',
        'class_id',
        'academic_session_id',
        'religion_id',
        'caste_category_id',
        'gender',
        'dob',
        'birthPlace',
        'admissionDate',
        'photo',
        'aadhar_copy',
        'birth_certificate',
        'father_name',
        'mother_name',
        'mobile1',
        'mobile2',
        'address',
        'nationality_id',
        'state_id',
        'aadharNo',
        'fatherOccupation',
        'motherOccupation',
        'mother_tongue_id',
        'created_at',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}

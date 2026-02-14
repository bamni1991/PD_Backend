<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHoliday extends Model
{
    use HasFactory;

    protected $table = 'school_holidays';

    protected $fillable = [
        'academic_session_id',
        'holiday_name',
        'holiday_date',
        'holiday_type', // Based on user request/mock data
    ];

    public $timestamps = false; // Usually holidays tables might not have timestamps, but check schema if needed. Assuming false or standard for now.

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
}

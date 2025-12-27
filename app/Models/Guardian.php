<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    // Schema has updated_at, created_at
    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'mobile',
        'alt_mobile',
        'address',
        'aadhar_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'guardian_id');
    }
}

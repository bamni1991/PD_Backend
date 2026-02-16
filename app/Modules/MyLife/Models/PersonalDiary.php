<?php

namespace App\Modules\MyLife\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PersonalDiary extends Model
{
    use HasFactory;

    protected $table = 'life_personal_diary';

    protected $fillable = [
        'title',
        'content',
        'entry_date',
    ];

    public $timestamps = false;

}

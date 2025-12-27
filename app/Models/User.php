<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Based on user schema, users table has `updated_at` and `created_at`.
    // Default timestamps = true is correct.

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'profile_image',
        'password',
        'role',
        'status',
        'last_login',
    ];

    protected $hidden = [
        'password',
        // 'remember_token', // Schema doesn't have remember_token in the dump!
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime' // Not in schema, but standard

    ];
}

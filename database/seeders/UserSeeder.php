<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // NOTE: The password in dump is plain text or customized hash (e.g. '11111111' or 'Teacher@123').
        // Laravel uses bcrypt. The user provided dump shows '11111111' in password column. 
        // If I put these directly, login won't work with Hash::check UNLESS the system is set to use plain text (bad) or these are actually placeholders.
        // HOWEVER, the login logic I wroted earlier uses Hash::make. The user's code previously used Hash::check.
        // But the user just replaced Hash::make with plain assignment in a previous turn: $request->parentMobile1.
        // This implies the user might be storing PLAIN TEXT passwords for now (insecure, but per user request/code).
        // I will insert them as is, matching the dump exactly.

        $data = [
            ['id' => 1, 'name' => 'School Admin', 'email' => 'admin@school.com', 'mobile' => '9999999999', 'profile_image' => 'profile_images/admin.png', 'password' => '11111111', 'role' => 'admin', 'status' => 'active', 'last_login' => '2025-12-21 11:45:29', 'created_at' => '2025-12-13 07:12:33', 'updated_at' => '2025-12-21 11:45:29'],
            ['id' => 2, 'name' => 'Anita Teacher', 'email' => 'teacher@school.com', 'mobile' => '9000000002', 'profile_image' => 'profile_images/teacher.png', 'password' => 'Teacher@123', 'role' => 'teacher', 'status' => 'active', 'last_login' => NULL, 'created_at' => '2025-12-13 07:12:43', 'updated_at' => '2025-12-13 07:12:43'],
            ['id' => 3, 'name' => 'Rahul Parent', 'email' => 'parent@school.com', 'mobile' => '9000000003', 'profile_image' => 'profile_images/parent.png', 'password' => 'Parent@123', 'role' => 'parent', 'status' => 'active', 'last_login' => NULL, 'created_at' => '2025-12-13 07:12:50', 'updated_at' => '2025-12-13 07:12:50'],
        ];
        DB::table('users')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicSessionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('academic_sessions')->insert([
            ['id' => 1, 'session_name' => '2024-2025', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:10'],
        ]);
    }
}

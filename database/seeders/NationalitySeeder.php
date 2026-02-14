<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nationality_name' => 'Indian', 'is_active' => 1, 'created_at' => '2025-12-13 12:46:41'],
        ];
        DB::table('nationalities')->insert($data);
    }
}

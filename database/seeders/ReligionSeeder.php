<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'religion_name' => 'Hindu', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:56'],
            ['id' => 2, 'religion_name' => 'Muslim', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:56'],
            ['id' => 3, 'religion_name' => 'Christian', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:56'],
            ['id' => 4, 'religion_name' => 'Buddhist', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:56'],
            ['id' => 5, 'religion_name' => 'Jain', 'is_active' => 1, 'created_at' => '2025-12-13 07:08:56'],
        ];
        DB::table('religions')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'class_name' => 'Nursery', 'created_at' => '2025-12-13 07:07:35'],
            ['id' => 2, 'class_name' => 'LKG', 'created_at' => '2025-12-13 07:07:35'],
            ['id' => 3, 'class_name' => 'UKG', 'created_at' => '2025-12-13 07:07:35'],
            ['id' => 4, 'class_name' => '1st', 'created_at' => '2025-12-13 07:07:35'],
            ['id' => 5, 'class_name' => '2nd', 'created_at' => '2025-12-13 07:07:35'],
        ];
        DB::table('classes')->insert($data);
    }
}

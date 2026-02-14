<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotherTongueSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'tongue_name' => 'Marathi', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 2, 'tongue_name' => 'Hindi', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 3, 'tongue_name' => 'English', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 4, 'tongue_name' => 'Gujarati', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 5, 'tongue_name' => 'Kannada', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 6, 'tongue_name' => 'Telugu', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 7, 'tongue_name' => 'Tamil', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 8, 'tongue_name' => 'Malayalam', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 9, 'tongue_name' => 'Punjabi', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 10, 'tongue_name' => 'Bengali', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 11, 'tongue_name' => 'Urdu', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 12, 'tongue_name' => 'Odia', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 13, 'tongue_name' => 'Assamese', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 14, 'tongue_name' => 'Konkani', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 15, 'tongue_name' => 'Sindhi', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 16, 'tongue_name' => 'Nepali', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 17, 'tongue_name' => 'Bhojpuri', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 18, 'tongue_name' => 'Rajasthani', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 19, 'tongue_name' => 'Maithili', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
            ['id' => 20, 'tongue_name' => 'Santhali', 'is_active' => 1, 'created_at' => '2025-12-13 12:45:58'],
        ];
        DB::table('mother_tongues')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'caste_name' => 'Maratha', 'category' => 'OPEN', 'created_at' => '2025-12-13 07:09:50'],
            ['id' => 2, 'caste_name' => 'Kunbi', 'category' => 'OBC', 'created_at' => '2025-12-13 07:09:50'],
            ['id' => 3, 'caste_name' => 'Mahar', 'category' => 'SC', 'created_at' => '2025-12-13 07:09:50'],
            ['id' => 4, 'caste_name' => 'Gond', 'category' => 'ST', 'created_at' => '2025-12-13 07:09:50'],
            ['id' => 5, 'caste_name' => 'Maratha', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 6, 'caste_name' => 'Brahmin', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 7, 'caste_name' => 'Rajput', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 8, 'caste_name' => 'Vaishya', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 9, 'caste_name' => 'Kayastha', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 10, 'caste_name' => 'Kshatriya', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 11, 'caste_name' => 'Jain', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:49:35'],
            ['id' => 12, 'caste_name' => 'Kunbi', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 13, 'caste_name' => 'Mali', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 14, 'caste_name' => 'Teli', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 15, 'caste_name' => 'Sutar', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 16, 'caste_name' => 'Lohar', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 17, 'caste_name' => 'Kumbhar', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 18, 'caste_name' => 'Yadav', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 19, 'caste_name' => 'Nai', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 20, 'caste_name' => 'Koli', 'category' => 'OBC', 'created_at' => '2025-12-13 12:49:51'],
            ['id' => 21, 'caste_name' => 'Mahar', 'category' => 'SC', 'created_at' => '2025-12-13 12:50:02'],
            ['id' => 22, 'caste_name' => 'Mang', 'category' => 'SC', 'created_at' => '2025-12-13 12:50:02'],
            ['id' => 23, 'caste_name' => 'Chambhar', 'category' => 'SC', 'created_at' => '2025-12-13 12:50:02'],
            ['id' => 24, 'caste_name' => 'Matang', 'category' => 'SC', 'created_at' => '2025-12-13 12:50:02'],
            ['id' => 25, 'caste_name' => 'Dom', 'category' => 'SC', 'created_at' => '2025-12-13 12:50:02'],
            ['id' => 26, 'caste_name' => 'Gond', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 27, 'caste_name' => 'Bhil', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 28, 'caste_name' => 'Warli', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 29, 'caste_name' => 'Kolam', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 30, 'caste_name' => 'Katkari', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 31, 'caste_name' => 'Thakur', 'category' => 'ST', 'created_at' => '2025-12-13 12:50:26'],
            ['id' => 32, 'caste_name' => 'Vadar', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 33, 'caste_name' => 'Banjara', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 34, 'caste_name' => 'Lamani', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 35, 'caste_name' => 'Pardhi', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 36, 'caste_name' => 'Dhangar', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 37, 'caste_name' => 'Ghisadi', 'category' => 'NT', 'created_at' => '2025-12-13 12:50:34'],
            ['id' => 38, 'caste_name' => 'Agri', 'category' => 'SBC', 'created_at' => '2025-12-13 12:50:42'],
            ['id' => 39, 'caste_name' => 'Koshti', 'category' => 'SBC', 'created_at' => '2025-12-13 12:50:42'],
            ['id' => 40, 'caste_name' => 'Halba Koshti', 'category' => 'SBC', 'created_at' => '2025-12-13 12:50:42'],
            ['id' => 41, 'caste_name' => 'EWS', 'category' => 'EWS', 'created_at' => '2025-12-13 12:50:47'],
            ['id' => 42, 'caste_name' => 'Other', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:50:47'],
            ['id' => 43, 'caste_name' => 'Not Disclosed', 'category' => 'OPEN', 'created_at' => '2025-12-13 12:50:47'],
        ];
        DB::table('caste_categories')->insert($data);
    }
}

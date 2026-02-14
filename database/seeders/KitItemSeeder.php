<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KitItemSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'item_name' => 'School Bag', 'price' => 1200.00, 'created_at' => '2025-12-13 07:08:32'],
            ['id' => 2, 'item_name' => 'Uniform', 'price' => 800.00, 'created_at' => '2025-12-13 07:08:32'],
            ['id' => 3, 'item_name' => 'Books Set', 'price' => 1500.00, 'created_at' => '2025-12-13 07:08:32'],
        ];
        DB::table('kit_items')->insert($data);
    }
}

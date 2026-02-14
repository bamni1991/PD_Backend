<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'state_name' => 'Maharashtra', 'country_id' => NULL, 'is_active' => 1, 'created_at' => '2025-12-13 12:47:01'],
        ];
        DB::table('states')->insert($data);
    }
}

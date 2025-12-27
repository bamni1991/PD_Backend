<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassFeeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'class_id' => 1, 'academic_session_id' => 1, 'fee_amount' => 15000.00, 'created_at' => '2025-12-13 07:10:22'],
            ['id' => 2, 'class_id' => 2, 'academic_session_id' => 1, 'fee_amount' => 18000.00, 'created_at' => '2025-12-13 07:10:22'],
            ['id' => 3, 'class_id' => 3, 'academic_session_id' => 1, 'fee_amount' => 20000.00, 'created_at' => '2025-12-13 07:10:22'],
        ];
        DB::table('class_fees')->insert($data);
    }
}

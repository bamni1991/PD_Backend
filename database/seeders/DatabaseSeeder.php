<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AcademicSessionSeeder::class,
            CasteCategorySeeder::class,
            ClassesSeeder::class,
            KitItemSeeder::class,
            MotherTongueSeeder::class,
            NationalitySeeder::class,
            ReligionSeeder::class,
            StateSeeder::class,
            UserSeeder::class,
            ClassFeeSeeder::class, // Depends on Classes and Sessions
        ]);
    }
}

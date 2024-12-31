<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/data/laravel_pos_villages.sql');
        if (File::exists($sqlPath)) {
            DB::unprepared(File::get($sqlPath));
            $this->command->info('Village data seeded successfully!');
        } else {
            $this->command->error('Village SQL file not found.');
        }
    }
}

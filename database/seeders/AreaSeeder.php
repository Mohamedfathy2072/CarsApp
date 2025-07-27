<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('data/all_areas.csv'), 'r');
        fgetcsv($file); // skip header
        while ($row = fgetcsv($file)) {
            DB::table('areas')->insert([
                'name' => $row[0],
                'governorate_id' => $row[1],
            ]);
            echo "Inserted area: " . $row[0] . " - Governorate ID: " . $row[1] . "\n";

        }
        fclose($file);
    }
}

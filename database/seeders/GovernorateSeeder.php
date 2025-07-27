<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('data/all_governorates.csv'), 'r');
        fgetcsv($file); // skip header
        while ($row = fgetcsv($file)) {
            DB::table('governorates')->insert([
                'name' => $row[1],
            ]);
            echo "Inserted: " . $row[1] . "\n";

        }
        fclose($file);
    }
}

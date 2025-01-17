<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = storage_path('app/public/excel/provinces.csv');

        if (($handle = fopen($csvFile, 'r')) !== false) {
            fgetcsv($handle);


            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                Province::updateOrCreate([
                    'name'  => $data[1]
                ],[
                    'id'            => $data[0],
                    'name'          => $data[1],
                    'description'   => $data[2]
                ]);
            }

            fclose($handle);
        }
    }
}

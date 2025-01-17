<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{

    public function run(): void
    {
        $csvFile = storage_path('app/public/excel/districts.csv');

        if (($handle = fopen($csvFile, 'r')) !== false) {
            fgetcsv($handle);


            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                District::updateOrCreate([
                    'name'  => $data[1]
                ],[
                    'id'            => $data[0],
                    'city_id'       => $data[1],
                    'name'          => $data[2],
                    'description'   => $data[3]
                ]);
            }

            fclose($handle);
        }
    }
}

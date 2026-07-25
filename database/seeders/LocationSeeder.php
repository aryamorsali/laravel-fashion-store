<?php

namespace Database\Seeders;

use App\Models\Market\City;
use App\Models\Market\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Tehran' => [
                'Tehran',
                'Islamshahr',
                'Shahriar',
            ],
            'Isfahan' => [
                'Isfahan',
                'Kashan',
                'Najafabad',
            ],
            'Fars' => [
                'Shiraz',
                'Marvdasht',
                'Jahrom',
            ],
        ];

        foreach ($data as $provinceName => $cities) {

            $province = Province::create([
                'name' => $provinceName
            ]);

            foreach ($cities as $city) {
                City::create([
                    'province_id' => $province->id,
                    'name' => $city
                ]);
            }
        }
    }
}

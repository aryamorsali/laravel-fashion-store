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
        $locations = [
            'Tehran' => ['Tehran', 'Islamshahr', 'Shahriar', 'Qods', 'Malard', 'Varamin', 'Pardis', 'Damavand', 'Ray'],
            'Isfahan' => ['Isfahan', 'Kashan', 'Najafabad', 'Shahin Shahr', 'Shahreza', 'Khomeyni Shahr', 'Fooladshahr'],
            'Fars' => ['Shiraz', 'Marvdasht', 'Jahrom', 'Fasa', 'Kazerun', 'Lar', 'Darab'],
            'Razavi Khorasan' => ['Mashhad', 'Neyshabur', 'Sabzevar', 'Torbat-e Heydarieh', 'Quchan', 'Kashmar', 'Gonabad'],
            'East Azerbaijan' => ['Tabriz', 'Maragheh', 'Marand', 'Mianeh', 'Ahar', 'Bonab', 'Bokan', 'Mahabad'],
            'Alborz' => ['Karaj', 'Fardis', 'Nazarabad', 'Hashtgerd', 'Taleqan'],
            'Mazandaran' => ['Sari', 'Babol', 'Amol', 'Qaem Shahr', 'Behshahr', 'Chalus', 'Tonekabon', 'Noshahr'],
            'Gilan' => ['Rasht', 'Bandar-e Anzali', 'Lahijan', 'Langarud', 'Talash', 'Astara', 'Fuman'],
            'Khuzestan' => ['Ahvaz', 'Dezful', 'Abadan', 'Khorramshahr', 'Mahshahr', 'Izeh', 'Masjed Soleyman'],
            'West Azerbaijan' => ['Urmia', 'Khoy', 'Miandoab', 'Mahabad', 'Bukan', 'Salmas'],
            'Kerman' => ['Kerman', 'Sirjan', 'Rafsanjan', 'Jiroft', 'Bam', 'Zarand'],
            'Markazi' => ['Arak', 'Saveh', 'Khomein', 'Mahallat', 'Delijan'],
            'Yazd' => ['Yazd', 'Meybod', 'Ardakan', 'Bafq', 'Mehriz'],
            'Qom' => ['Qom', 'Jafariyeh', 'Qanavat'],
            'Qazvin' => ['Qazvin', 'Takestan', 'Alvand', 'Abyek'],
            'Semnan' => ['Semnan', 'Shahroud', 'Damghan', 'Garmsar'],
            'Hamadan' => ['Hamadan', 'Malayer', 'Nahavand', 'Tuyserkan'],
            'Kermanshah' => ['Kermanshah', 'Islamabad-e Gharb', 'Kangavar', 'Sunqur', 'Paveh'],
            'Kurdistan' => ['Sanandaj', 'Saqqez', 'Marivan', 'Baneh', 'Bijar'],
            'Lorestan' => ['Khorramabad', 'Borujerd', 'Dorud', 'Aligudarz', 'Kuhdasht'],
            'Hormozgan' => ['Bandar Abbas', 'Minab', 'Qeshm', 'Kish', 'Bandar Lengeh', 'Jask'],
            'Bushehr' => ['Bushehr', 'Borazjan', 'Kangan', 'Genaveh', 'Asaluyeh'],
            'Zanjan' => ['Zanjan', 'Abhar', 'Khorramdarreh', 'Qeydar'],
            'Golestan' => ['Gorgan', 'Gonbad-e Kavus', 'Aliabad-e Katul', 'Bandar-e Torkaman'],
            'Ardabil' => ['Ardabil', 'Parsabad', 'Meshgin Shahr', 'Khalkhal'],
            'Sistan and Baluchestan' => ['Zahedan', 'Chabahar', 'Zabol', 'Iranshahr', 'Saravan'],
            'Chahar Mahaal and Bakhtiari' => ['Shahrekord', 'Borujen', 'Lordegan'],
            'Kohgiluyeh and Boyer-Ahmad' => ['Yasuj', 'Dogonbadan', 'Dehdasht'],
            'South Khorasan' => ['Birjand', 'Qaen', 'Tabas', 'Ferdows'],
            'North Khorasan' => ['Bojnourd', 'Shirvan', 'Esfarayen'],
            'Ilam' => ['Ilam', 'Dehloran', 'Eyvan', 'Mehran'],
        ];

        foreach ($locations as $provinceName => $cities) {
            $province = Province::updateOrCreate(
                ['name' => $provinceName],
                ['name' => $provinceName]
            );

            foreach ($cities as $cityName) {
                City::updateOrCreate(
                    [
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ],
                    [
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]
                );
            }
        }
    }
}

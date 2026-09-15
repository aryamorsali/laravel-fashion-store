<?php

namespace Database\Seeders;

use App\Models\Setting\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'id'         => 1,
                'key'        => 'site_name',
                'value'      => 'CozaShop',
                'status'     => 1,
            ],
            [
                'id'         => 2,
                'key'        => 'site_email',
                'value'      => 'info@example.com',
                'status'     => 1,
            ],
            [
                'id'         => 3,
                'key'        => 'site_logo',
                'value'      => 'images/setting/2026/08/08/1786211425.png',
                'status'     => 1,
            ],
            [
                'id'         => 17,
                'key'        => 'site_phone',
                'value'      => '+98-912-0000000',
                'status'     => 1,
            ],
            [
                'id'         => 18,
                'key'        => 'site_address',
                'value'      => "No. 12, Mi'alan Street, Tehran",
                'status'     => 1,
            ],
            [
                'id'         => 19,
                'key'        => 'site_description',
                'value'      => 'The best clothing store with the latest models',
                'status'     => 1,
            ],
            [
                'id'         => 20,
                'key'        => 'currency',
                'value'      => 'Dollar',
                'status'     => 1,
            ],
            [
                'id'         => 21,
                'key'        => 'shipping_policy',
                'value'      => 'Free shipping for purchases over 500 thousand Tomans',
                'status'     => 1,
            ],
            [
                'id'         => 22,
                'key'        => 'return_policy',
                'value'      => 'Returns possible within 7 days',
                'status'     => 1,
            ],
            [
                'id'         => 23,
                'key'        => 'facebook_link',
                'value'      => 'https://facebook.com/aryamorsali',
                'status'     => 1,
            ],
            [
                'id'         => 24,
                'key'        => 'instagram_link',
                'value'      => 'https://instagram.com/aryamorsali',
                'status'     => 1,
            ],
            [
                'id'         => 25,
                'key'        => 'twitter_link',
                'value'      => 'https://twitter.com/aryamorsali',
                'status'     => 1,
            ],
            [
                'id'         => 26,
                'key'        => 'maintenance_mode',
                'value'      => '0',
                'status'     => 1,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['id' => $setting['id']],
                $setting
            );
        }
    }
}

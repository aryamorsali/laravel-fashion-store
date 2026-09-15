<?php

namespace Database\Seeders;

use App\Models\Setting\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'CozaShop'],
            ['key' => 'site_email', 'value' => 'info@example.com'],
            ['key' => 'site_logo', 'value' => 'images\setting\2026\08\08\1786211425.png'],
            ['key' => 'site_phone', 'value' => '+98-912-0000000'],
            ['key' => 'site_address', 'value' => "No. 12, Mi'alan Street, Tehran"],
            ['key' => 'site_description', 'value' => 'The best clothing store with the latest models'],
            ['key' => 'currency', 'value' => 'IRR'],
            ['key' => 'shipping_policy', 'value' => 'Free shipping for purchases over 500 thousand Tomans. Yes!'],
            ['key' => 'return_policy', 'value' => 'Returns possible within 7 days'],
            ['key' => 'facebook_link', 'value' => 'https://facebook.com/mystore'],
            ['key' => 'instagram_link', 'value' => 'https://instagram.com/mystore'],
            ['key' => 'twitter_link', 'value' => 'https://twitter.com/mystore'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}

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
            ['key' => 'site_name', 'value' => 'فروشگاه لباس من'],
            ['key' => 'site_email', 'value' => 'info@example.com'],
            ['key' => 'site_logo', 'value' => 'logo.png'],
            ['key' => 'site_phone', 'value' => '+98-912-0000000'],
            ['key' => 'site_address', 'value' => 'تهران، خیابان مثال، پلاک 12'],
            ['key' => 'site_description', 'value' => 'بهترین فروشگاه لباس با جدیدترین مدل‌ها'],
            ['key' => 'currency', 'value' => 'IRR'],
            ['key' => 'shipping_policy', 'value' => 'ارسال رایگان برای خریدهای بالای 500 هزار تومان'],
            ['key' => 'return_policy', 'value' => 'امکان مرجوعی تا 7 روز'],
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

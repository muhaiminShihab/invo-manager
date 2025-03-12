<?php

namespace Database\Seeders;

use App\Models\Setting;
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
            ['key' => 'name', 'value' => 'Due Invoice Manager'],
            ['key' => 'email', 'value' => 'app@example.com'],
            ['key' => 'phone', 'value' => '+880 1234-567890'],
            ['key' => 'address', 'value' => 'Mirpur, Dhaka-1206, Bangladesh']
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}

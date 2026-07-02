<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxSetting;

class TaxSettingSeeder extends Seeder
{
    public function run(): void
    {
        TaxSetting::firstOrCreate(
            ['key' => 'tax_rate'],
            ['value' => '0', 'description' => 'Tax rate in percentage (%)']
        );
    }
}
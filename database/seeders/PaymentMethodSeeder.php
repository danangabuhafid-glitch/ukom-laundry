<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Cash',
            'code' => 'cash',
            'is_active' => true,
            'is_system' => true,
        ]);

        PaymentMethod::create([
            'name' => 'QRIS',
            'code' => 'qris',
            'is_active' => true,
            'is_system' => true,
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeOfService;

class TypeOfServiceSeeder extends Seeder
{
    public function run(): void
    {
        TypeOfService::create(['service_name' => 'Cuci dan Gosok', 'price' => 5000]);
        TypeOfService::create(['service_name' => 'Hanya Cuci', 'price' => 4500]);
        TypeOfService::create(['service_name' => 'Hanya Gosok', 'price' => 5000]);
        TypeOfService::create(['service_name' => 'Laundry Besar', 'price' => 7000]);
    }
}

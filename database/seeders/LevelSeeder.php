<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::create(['level_name' => 'Administrator']);
        Level::create(['level_name' => 'Operator']);
        Level::create(['level_name' => 'Pimpinan']);
    }
}

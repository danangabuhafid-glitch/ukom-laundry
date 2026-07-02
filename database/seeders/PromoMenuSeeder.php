<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class PromoMenuSeeder extends Seeder
{
    public function run()
    {
        $parent = Menu::create([
            'name' => 'Marketing & Promo',
            'icon' => 'ti-speakerphone',
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Promo Settings',
            'icon' => 'ti-discount',
            'route_name' => 'promos.index',
            'permission_name' => 'read-promos',
            'parent_id' => $parent->id,
            'order' => 1,
        ]);
    }
}

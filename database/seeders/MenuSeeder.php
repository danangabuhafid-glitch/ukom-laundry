<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing menus to ensure clean state (runs only once)
        \App\Models\Menu::query()->delete();

        // Master Data Group
        $masterData = \App\Models\Menu::create([
            'name' => 'Master Data',
            'icon' => 'ti-database',
            'route_name' => null,
            'order' => 1,
            'permission_name' => null,
        ]);

        $masterData->children()->createMany([
            ['name' => 'Customer', 'icon' => 'ti-circle', 'route_name' => 'master.customers.index', 'order' => 1, 'permission_name' => 'read-customers'],
            ['name' => 'Service', 'icon' => 'ti-circle', 'route_name' => 'master.services.index', 'order' => 2, 'permission_name' => 'read-services'],
            ['name' => 'User', 'icon' => 'ti-circle', 'route_name' => 'master.users.index', 'order' => 3, 'permission_name' => 'read-users'],
            ['name' => 'Roles', 'icon' => 'ti-circle', 'route_name' => 'roles.index', 'order' => 4, 'permission_name' => 'read-roles'],
        ]);

        // System Settings Group
        $systemSettings = \App\Models\Menu::create([
            'name' => 'System Settings',
            'icon' => 'ti-settings',
            'route_name' => null,
            'order' => 2,
            'permission_name' => null,
        ]);

        $systemSettings->children()->createMany([
            ['name' => 'Web Settings', 'icon' => 'ti-circle', 'route_name' => 'settings.index', 'order' => 1, 'permission_name' => 'read-settings'],
            ['name' => 'Menu Configuration', 'icon' => 'ti-circle', 'route_name' => 'menus.index', 'order' => 2, 'permission_name' => 'read-menus'],
        ]);

        // Operations Group
        $operations = \App\Models\Menu::create([
            'name' => 'Operations',
            'icon' => 'ti-briefcase',
            'route_name' => null,
            'order' => 3,
            'permission_name' => null,
        ]);

        $operations->children()->createMany([
            ['name' => 'Transaction', 'icon' => 'ti-circle', 'route_name' => 'transactions.index', 'order' => 1, 'permission_name' => 'read-transactions'],
            ['name' => 'Pickup', 'icon' => 'ti-circle', 'route_name' => 'pickups.index', 'order' => 2, 'permission_name' => 'read-transactions'],
        ]);

        // Management Group
        $management = \App\Models\Menu::create([
            'name' => 'Management',
            'icon' => 'ti-chart-bar',
            'route_name' => null,
            'order' => 4,
            'permission_name' => null,
        ]);

        $management->children()->createMany([
            ['name' => 'Reports', 'icon' => 'ti-circle', 'route_name' => 'reports.index', 'order' => 1, 'permission_name' => 'read-reports'],
        ]);

        // Financial Settings Group
        $financial = \App\Models\Menu::create([
            'name' => 'Financial Settings',
            'icon' => 'ti-cash',
            'route_name' => null,
            'order' => 5,
            'permission_name' => null,
        ]);

        $financial->children()->createMany([
            ['name' => 'Tax', 'icon' => 'ti-circle', 'route_name' => 'financial.tax.index', 'order' => 1, 'permission_name' => 'read-taxes'],
            ['name' => 'Payment Method', 'icon' => 'ti-circle', 'route_name' => 'financial.payment-methods.index', 'order' => 2, 'permission_name' => 'read-payment-methods'],
        ]);
    }
}
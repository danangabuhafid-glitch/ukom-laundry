<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions List
        $permissions = [
            'read-transactions', 'create-transactions', 'update-transactions', 'delete-transactions',
            'read-customers', 'create-customers', 'update-customers', 'delete-customers',
            'read-services', 'create-services', 'update-services', 'delete-services',
            'read-users', 'create-users', 'update-users', 'delete-users',
            'read-roles', 'update-roles',
            'read-settings', 'update-settings',
            'read-reports',
            'read-menus', 'create-menus', 'update-menus', 'delete-menus',
            'read-promos', 'create-promos', 'update-promos', 'delete-promos',
            'read-taxes', 'update-taxes',
            'read-payment-methods', 'create-payment-methods', 'update-payment-methods', 'delete-payment-methods',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrator']);
        $operator = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Operator']);
        $pimpinan = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Pimpinan']);

        // Give all permissions to admin
        $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());
        
        // Give basic permissions to operator
        $operator->syncPermissions([
            'read-transactions', 'create-transactions',
            'read-customers', 'create-customers',
            'read-services'
        ]);

        // Give read permissions to pimpinan
        $pimpinan->syncPermissions([
            'read-transactions', 'read-customers', 'read-services', 'read-users', 'read-reports'
        ]);

        // Assign Roles to existing seeded users
        $adminUser = \App\Models\User::where('username', 'admin')->first();
        if ($adminUser) $adminUser->assignRole('Administrator');

        $operatorUser = \App\Models\User::where('username', 'operator')->first();
        if ($operatorUser) $operatorUser->assignRole('Operator');

        $pimpinanUser = \App\Models\User::where('username', 'pimpinan')->first();
        if ($pimpinanUser) $pimpinanUser->assignRole('Pimpinan');
    }
}

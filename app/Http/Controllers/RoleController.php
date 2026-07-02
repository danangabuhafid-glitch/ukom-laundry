<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($perm) {
            $parts = explode('-', $perm->name);
            return end($parts);
        });
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function edit(Role $role)
    {
        // Get all permissions grouped by the module name (the part after the hyphen)
        $permissions = Permission::all()->groupBy(function($perm) {
            $parts = explode('-', $perm->name);
            return end($parts); // grouped by transactions, customers, etc.
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $permissions = $request->input('permissions', []);
        
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', "Permissions for {$role->name} updated successfully.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Administrator', 'Operator', 'Pimpinan'])) {
            return redirect()->route('roles.index')->with('error', 'Default roles cannot be deleted.');
        }
        
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}

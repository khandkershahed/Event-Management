<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionGroups = [
            'Dashboard' => ['view dashboard'],
            'Role' => ['view role', 'create role', 'edit role', 'delete role', 'give permission role', 'store permission role'],
            'Permission' => ['view permission', 'create permission', 'edit permission', 'delete permission'],
            'User' => ['view user', 'create user', 'show user', 'edit user', 'delete user'],
            'Organizer' => ['organizer.view', 'organizer.create', 'organizer.edit', 'organizer.approve', 'organizer.reject', 'organizer.suspend'],
            'Venue' => ['view venue', 'create venue', 'show venue', 'edit venue', 'delete venue'],
            'Event' => ['view event', 'create event', 'show event', 'edit event', 'delete event'],
            'Setting' => ['view setup', 'view setting', 'update setting'],
            'Report' => ['view report'],
        ];

        $roleAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'admin',
                ], [
                    'group_name' => $group,
                ]);

                if (! $roleAdmin->hasPermissionTo($permission)) {
                    $roleAdmin->givePermissionTo($permission);
                }
            }
        }

        Admin::query()->each(function (Admin $admin) use ($roleAdmin): void {
            if (! $admin->hasRole($roleAdmin)) {
                $admin->assignRole($roleAdmin);
            }
        });
    }
}

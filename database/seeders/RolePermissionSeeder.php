<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'group_name' => 'Dashboard',
                'permissions' => [
                    'view dashboard',
                ],
            ],
            [
                'group_name' => 'Report',
                'permissions' => [
                    'view report',
                ],
            ],
            [
                'group_name' => 'Role',
                'permissions' => [
                    'view role',
                    'edit role',
                    'delete role',
                    'create role',
                    'give permission role',
                    'store permission role',
                ],
            ],
            [
                'group_name' => 'Permission',
                'permissions' => [
                    'view permission',
                    'edit permission',
                    'delete permission',
                    'create permission',
                ],
            ],
            [
                'group_name' => 'Staff',
                'permissions' => [
                    'view staff',
                    'create staff',
                    'show staff',
                    'edit staff',
                    'delete staff',
                ],
            ],
            [
                'group_name' => 'User',
                'permissions' => [
                    'view user',
                    'create user',
                    'show user',
                    'edit user',
                    'delete user',
                ],
            ],
            [
                'group_name' => 'Fee Waiver',
                'permissions' => [
                    'view fee-waiver',
                    'create fee-waiver',
                    'show fee-waiver',
                    'edit fee-waiver',
                    'delete fee-waiver',
                ],
            ],
            [
                'group_name' => 'Fee',
                'permissions' => [
                    'view fee',
                    'create fee',
                    'show fee',
                    'edit fee',
                    'delete fee',
                    'fee collection',
                ],
            ],
            [
                'group_name' => 'Backup',
                'permissions' => [
                    'download backup',
                ],
            ],
            [
                'group_name' => 'Setting',
                'permissions' => [
                    'view setup',
                    'view setting',
                    'update setting',
                ],
            ],

        ];

        $roleAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);

        foreach ($permissions as $permission) {

            $permissions = $permission['group_name'];
            foreach ($permission['permissions'] as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'group_name' => $permissions,
                    'guard_name' => 'admin',
                ]);
                $roleAdmin->givePermissionTo($permission);
            }
        }
        $admin = Admin::where('email', 'khandkershahed23@gmail.com')->first();
        if ($admin) {
            $admin->assignRole($roleAdmin);
        }
    }
}

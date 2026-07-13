<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();

        $adminRole = Role::where('name', 'Admin')->first();

        foreach ($permissions as $permission) {
            DB::table('role_permissions')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id
            ]);
        }

        $writerRole = Role::where('name', 'Writer')->first();

        foreach ($permissions as $permission) {
            if (!(in_array($permission->name, ['edit_roles']))) {
                DB::table('role_permissions')->insert([
                    'role_id' => $writerRole->id,
                    'permission_id' => $permission->id
                ]);
            }
        }

        $viewerRole = Role::where('name', 'Viewer')->first();
        $viewerPermissions = ['view_users', 'view_roles', 'view_products', 'view_orders'];

        foreach ($permissions as $permission) {
            if (in_array($permission->name, $viewerPermissions)) {
                DB::table('role_permissions')->insert([
                    'role_id' => $viewerRole->id,
                    'permission_id' => $permission->id
                ]);
            }
        }

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = Permission::all();
        $admin = Role::where('name', 'Admin')->first();
        $writer = Role::where('name', 'Writer')->first();
        $viewer = Role::where('name', 'Viewer')->first();

        foreach($permissions as $permission) { 
            DB::table('role_permission')->insert([
                'role_id' => $admin->id,
                'permission_id' => $permission->id,
            ]);
         }

        foreach($permissions as $permission) {
            if (!in_array($permission->name, ['edit_roles'])) {
                DB::table('role_permission')->insert([
                    'role_id' => $writer->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }

        $viewerRoles = ['view_users', 'view_roles', 'view_products', 'view_orders'];
        foreach($permissions as $permission) {
            if (in_array($permission->name, $viewerRoles)) {
                DB::table('role_permission')->insert([
                    'role_id' => $viewer->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}

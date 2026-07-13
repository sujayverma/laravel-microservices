<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            'view_users',
            'edit_users',
            'view_roles',
            'edit_roles',
            'view_products',
            'edit_products',
            'view_orders',
            'edit_orders'
        ];
        Permission::insert(array_map(fn($permission) => ['name' => $permission], $permissions));
    }
}

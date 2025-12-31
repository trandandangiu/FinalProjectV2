<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [
            'manager_users',
            'manager_products',
            'manager_orders',
            'manager_categories',
            'manager_contact'
        ];
    foreach($permissions as $permission) 
    {
            \App\Models\Permission::create(['name' => $permission]);
        }
    }
}

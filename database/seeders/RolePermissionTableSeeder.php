<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission; 

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $adminRole = \App\Models\Role::where('name', 'admin')->first();
    $staffRole = \App\Models\Role::where('name', 'staff')->first();
    
    $allpermissions = Permission::all();
    
    // Admin have all permissions
    $adminRole->permissions()->sync($allpermissions->pluck('id'));
    
    // Staff have some permissions
    $staffPermissions = Permission::whereIn('name', [  
        'manager_products',
        'manager_contact'
    ])->get();
    
    $staffRole->permissions()->sync($staffPermissions->pluck('id'));  
}
}

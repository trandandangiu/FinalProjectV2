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
    
    // Admin có tất cả permissions
    $adminRole->permissions()->sync($allpermissions->pluck('id'));
    
    // Staff chỉ có 2 permissions: products và contact
    // KHÔNG có categories, users, orders
    $staffPermissions = Permission::whereIn('name', [  
        'manager_products',  // Chỉ sản phẩm
        'manager_contact'    // Chỉ liên hệ
        // KHÔNG có: manager_categories, manager_users, manager_orders
    ])->get();
    
    $staffRole->permissions()->sync($staffPermissions->pluck('id'));  
}
}

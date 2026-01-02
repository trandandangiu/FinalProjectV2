<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'Admin@example.com',
            'password' => bcrypt('123'),
            'phone_number'=>'0827777721',
            'status'=>'active',
            'avatar'=>'',
            'address'=>'Ho Chi Minh City',
            'role_id'=>'',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
                \App\Models\User::create([
            'name' => 'Staff User',
            'email' => 'Staff@example.com',
            'password' => bcrypt('123'),
            'phone_number'=>'0984452479',
            'status'=>'pending',
            'avatar'=>'',
            'address'=>'Ho Chi Minh City',
            'role_id'=>5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

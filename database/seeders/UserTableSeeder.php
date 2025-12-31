<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Tran Huynh Minh Tri',
            'email' => 'THMT@example.com',
            'password' => bcrypt('123'),
            'phone_number'=>'0123456789',
            'status'=>'pending',
            'avatar'=>'',
            'address'=>'Ho Chi Minh City',
            'role_id'=>4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            \App\Models\User::create([
            'name' => 'Lam Chi Kiet',
            'email' => 'LCK@example.com',
            'password' => bcrypt('123'),
            'phone_number'=>'012345678',
            'status'=>'pending',
            'avatar'=>'',
            'address'=>'Ha Noi City',
            'role_id'=>5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            \App\Models\User::create([
            'name' => 'Nguyen Huu Hoang',
            'email' => 'NHH@example.com',
            'password' => bcrypt('123'),
            'phone_number'=>'01234567',
            'status'=>'pending',
            'avatar'=>'',
            'address'=>'Da Nang City',
            'role_id'=>6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}

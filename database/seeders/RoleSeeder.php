<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@roadhealth.ai'],
            [
                'name' => 'System Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'phone' => '9876543210',
                'territory' => 'Delhi Municipal',
                'zone' => 'Central Zone',
                'ward' => 'Ward 12',
                'area' => 'Connaught Place',
                'status' => 'approved',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'officer@roadhealth.ai'],
            [
                'name' => 'Government Officer',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'officer',
                'phone' => '9876543211',
                'employee_id' => 'OFF-001',
                'department' => 'Road Infrastructure',
                'territory' => 'Delhi Municipal',
                'zone' => 'Central Zone',
                'ward' => 'Ward 12',
                'area' => 'Connaught Place',
                'status' => 'approved',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'staff@roadhealth.ai'],
            [
                'name' => 'Field Staff',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'staff',
                'phone' => '9876543212',
                'employee_id' => 'STF-001',
                'department' => 'Maintenance Operations',
                'territory' => 'Delhi Municipal',
                'zone' => 'Central Zone',
                'ward' => 'Ward 12',
                'area' => 'Connaught Place',
                'status' => 'approved',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'citizen@roadhealth.ai'],
            [
                'name' => 'John Doe',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'citizen',
                'phone' => '9876543213',
                'territory' => 'Delhi Municipal',
                'zone' => 'Central Zone',
                'ward' => 'Ward 12',
                'area' => 'Connaught Place',
                'status' => 'approved',
            ]
        );
    }
}

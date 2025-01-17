<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{

    public function run(): void
    {
        $roles = ['superadmin'];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'name'  => $role
            ],[
                'name' => $role, 'guard_name' => 'web'
            ]);
        }
    }
}

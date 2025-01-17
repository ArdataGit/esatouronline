<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{

    public function run(): void
    {
        $data = [
            ['name'  => 'Super Admin', 'email' => 'superadmin@gmail.com', 'password' => Hash::make('secret'), 'role' => 'superadmin'],
        ];

        foreach ($data as $key => $value) {
            $param = [
                'name'      => $value['name'],
                'email'     => $value['email'],
                'password'  => $value['password']    
            ];

            $user = User::updateOrCreate([
                'name'  => $value['name']
            ], $param);

            $user->syncRoles([]);
            $user->assignRole($value['role']);
        }
    }
}

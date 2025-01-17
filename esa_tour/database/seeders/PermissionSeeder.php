<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'view invoice',
            'create invoice',
            'edit invoice',
            'delete invoice'
        ];

        $roles = Role::all();

        foreach ($roles as $key => $value) {
            if ($value->name == 'superadmin') {
                foreach ($data as $key => $value2) {
                    Permission::updateOrCreate([
                        'name' => $value2
                    ],[
                        'name' => $value2
                    ]);

                    $value->givePermissionTo($value2);
                }
            }
        }
    }
}

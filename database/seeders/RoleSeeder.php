<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $Roles= [
            'Root',
            'Admin',
            'Televendeurs',
            'Marketing',
            'Commercial',
            'Direction'
        ];

        foreach ($Roles as $Role) {
            Role::firstOrCreate(
                [
                    'name' => $Role,
                    'guard_name' => 'web',
                ]
            );
        }
    }
}

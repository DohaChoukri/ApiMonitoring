<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'ca:show-dashboard',
            'ca:show-menu-partners',
            'ca:show-menu-partners-details',
            'ca:show-menu-products',
            'ca:show-menu-products-price-listes',
            'ca:show-details-promotions',
            'ca:show-dashboard-promotions'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web',
                ]
            );
        }
    }
}

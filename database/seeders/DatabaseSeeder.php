<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Appeler les seeders dans le bon ordre
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Donner automatiquement les permissions aux rôles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        $rolesPermissions = [
            'Root' => [
                'ca:show-dashboard',
                'ca:show-menu-partners',
                'ca:show-menu-partners-details',
                'ca:show-menu-products',
                'ca:show-menu-products-price-listes',
                'ca:show-details-promotions',
                'ca:show-dashboard-promotions',
            ],
            'Admin' => [
                'ca:show-dashboard',
                'ca:show-menu-partners',
                'ca:show-menu-partners-details',
                'ca:show-menu-products',
                'ca:show-menu-products-price-listes',
                'ca:show-details-promotions',
                'ca:show-dashboard-promotions',
            ],
            'Televendeurs' => [
                'ca:show-dashboard',
                'ca:show-menu-partners',
                'ca:show-menu-partners-details',
                'ca:show-menu-products',
            ],
            'Marketing' => [
                'ca:show-dashboard-promotions',
                'ca:show-details-promotions',
            ],
            'Commercial' => [
                'ca:show-dashboard',
                'ca:show-menu-partners',
                'ca:show-menu-partners-details',
            ],
            'Direction' => [
                'ca:show-dashboard',
            ],
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($permissions);
        }
    }
}

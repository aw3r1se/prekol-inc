<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $dashboardPermissions = [
            [ 'name' => 'dashboard.access' ],
        ];

        $userPermissions = [
            [ 'name' => 'users.list' ],
            [ 'name' => 'users.show' ],
            [ 'name' => 'users.update' ],
            [ 'name' => 'users.delete' ],
        ];

        $productPermissions = [
            [ 'name' => 'products.list' ],
            [ 'name' => 'products.show' ],
            [ 'name' => 'products.create' ],
            [ 'name' => 'products.update' ],
            [ 'name' => 'products.update.info' ],
            [ 'name' => 'products.update.price' ],
            [ 'name' => 'products.update.media' ],
            [ 'name' => 'products.update.meta' ],
            [ 'name' => 'products.publish' ],
            [ 'name' => 'products.delete' ],
        ];

        $orderPermissions = [
            [ 'name' => 'orders.list' ],
            [ 'name' => 'orders.show' ],
            [ 'name' => 'orders.create' ],
            [ 'name' => 'orders.update' ],
            [ 'name' => 'orders.delete' ],
        ];

        $all = Arr::collapse([
            $dashboardPermissions,
            $userPermissions,
            $productPermissions,
            $orderPermissions,
            /** continue */
        ]);

        foreach ($all as $permission) {
            Permission::create($permission);
        }

        $roles = [
            [ 'name' => 'boss' ],
            [ 'name' => 'manager' ],
            [ 'name' => 'seo' ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        /** @var Role $boss */
        $boss = Role::query()
            ->firstWhere('name', 'boss');
        $boss->givePermissionTo(
            ...collect($all)
                ->pluck('name')
                ->toArray()
        );

        /** @var User $firstUser */
        $firstUser = User::query()
            ->orderBy('created_at')
            ->first();

        $firstUser->assignRole($boss);
    }
}

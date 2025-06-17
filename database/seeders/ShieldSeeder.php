<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = ['user', 'participant', 'activity'];
        $specialResource = 'activity::type';

        $actions = [
            'full_management' => ['view_any', 'view', 'create', 'update', 'delete', 'delete_any'],
            'limited_management' => ['view_any', 'view', 'create', 'update'],
            'read_only' => ['view_any', 'view'],
        ];

        $superAdminPermissions = [
            'view_role', 'view_any_role', 'create_role', 'update_role', 'delete_role', 'delete_any_role',
        ];
        foreach ($resources as $resource) {
            $superAdminPermissions = array_merge(
                $superAdminPermissions,
                array_map(fn ($action): string => "{$action}_{$resource}", $actions['full_management'])
            );
        }
        $superAdminPermissions = array_merge(
            $superAdminPermissions,
            array_map(fn ($action): string => "{$action}_{$specialResource}", $actions['full_management'])
        );

        $fullManagementPermissions = [];
        foreach ($resources as $resource) {
            $fullManagementPermissions = array_merge(
                $fullManagementPermissions,
                array_map(fn ($action): string => "{$action}_{$resource}", $actions['full_management'])
            );
        }
        $fullManagementPermissions = array_merge(
            $fullManagementPermissions,
            array_map(fn ($action): string => "{$action}_{$specialResource}", $actions['full_management'])
        );

        $limitedManagementPermissions = [];
        foreach ($resources as $resource) {
            $limitedManagementPermissions = array_merge(
                $limitedManagementPermissions,
                array_map(fn ($action): string => "{$action}_{$resource}", $actions['limited_management'])
            );
        }
        $limitedManagementPermissions = array_merge(
            $limitedManagementPermissions,
            array_map(fn ($action): string => "{$action}_{$specialResource}", $actions['limited_management'])
        );

        $readOnlyPermissions = [];
        foreach ($resources as $resource) {
            $readOnlyPermissions = array_merge(
                $readOnlyPermissions,
                array_map(fn ($action): string => "{$action}_{$resource}", $actions['read_only'])
            );
        }
        $readOnlyPermissions = array_merge(
            $readOnlyPermissions,
            array_map(fn ($action): string => "{$action}_{$specialResource}", $actions['read_only'])
        );

        $rolesWithPermissions = [
            [
                'name' => 'super_admin',
                'guard_name' => 'filament',
                'permissions' => $superAdminPermissions,
            ],
            [
                'name' => 'full_management',
                'guard_name' => 'filament',
                'permissions' => $fullManagementPermissions,
            ],
            [
                'name' => 'limited_management',
                'guard_name' => 'filament',
                'permissions' => $limitedManagementPermissions,
            ],
            [
                'name' => 'read_only',
                'guard_name' => 'filament',
                'permissions' => $readOnlyPermissions,
            ],
        ];

        $rolesWithPermissionsJson = json_encode($rolesWithPermissions);

        static::makeRolesWithPermissions($rolesWithPermissionsJson);
        static::makeDirectPermissions('[]');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);

        $filamentSuperAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'filament')->first();

        $superAdmin->assignRole($filamentSuperAdminRole);

        $readOnlyAdmin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $filamentReadOnlyRole = Role::where('name', 'read_only')->where('guard_name', 'filament')->first();

        $readOnlyAdmin->assignRole($filamentReadOnlyRole);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}

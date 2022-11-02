<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BasicAdminPermissionSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // create permissions
        $permissions = [
            'Дозвіл список',
            'Дозвіл створити',
            'Дозвіл редагувати',
            'Дозвіл видалити',
            'Роль список',
            'Роль створити',
            'Роль редагувати',
            'Роль видалити',
            'Інцидент список',
            'Інцидент створити',
            'Інцидент редагувати',
            'Інцидент видалити',
            'Користувач статистика',
            'Користувач список',
            'Користувач активувати',
            'Користувач редагувати',
            'Користувач видалити',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Користувач']);
        $role1->givePermissionTo('Інцидент список');
        $role1->givePermissionTo('Інцидент створити');

        $role2 = Role::create(['name' => 'Адміністратор']);
        $role2->givePermissionTo('Інцидент список');
        $role2->givePermissionTo('Інцидент створити');
        $role2->givePermissionTo('Користувач статистика');
        $role2->givePermissionTo('Користувач список');
        $role2->givePermissionTo('Користувач активувати');
        $role2->givePermissionTo('Користувач редагувати');
        $role2->givePermissionTo('Користувач видалити');

        $role3 = Role::create(['name' => 'Супер Адміністратор']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'surname' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'is_activated' => true,
        ]);
        $user->assignRole($role3);
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@admin.com',
        ]);
        $user->assignRole($role2);
        $user = \App\Models\User::factory()->create([
            'name' => 'User',
            'surname' => 'User',
            'email' => 'user@user.com',
        ]);
        $user->assignRole($role1);
    }
}

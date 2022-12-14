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
        $role2->givePermissionTo('Користувач статистика');
        $role2->givePermissionTo('Користувач список');
        $role2->givePermissionTo('Користувач активувати');

        $role3 = Role::create(['name' => 'Супер Адміністратор']);

        $role4 = Role::create(['name' => 'Аналітик']);
        $role4->givePermissionTo('Інцидент редагувати');
        $role4->givePermissionTo('Інцидент видалити');

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Аналітик',
            'surname' => 'Surname',
            'email' => 'analyst@analyst.com',
        ]);
        $user->assignRole($role4);
        $user = \App\Models\User::factory()->create([
            'name' => 'Супер Адміністратор',
            'surname' => 'Surname',
            'email' => 'superadmin@superadmin.com',
            'is_activated' => 'Так',
        ]);
        $user->assignRole($role3);
        $user = \App\Models\User::factory()->create([
            'name' => 'Адміністратор',
            'surname' => 'Surname',
            'email' => 'admin@admin.com',
        ]);
        $user->assignRole($role2);
        $user = \App\Models\User::factory()->create([
            'name' => 'Користувач',
            'surname' => 'Surname',
            'email' => 'user@user.com',
        ]);
        $user->assignRole($role1);
    }
}

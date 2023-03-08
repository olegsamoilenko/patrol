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
            'Подія список',
            'Подія створити',
            'Подія редагувати',
            'Подія видалити',
            'Подія на головній список',
            'Подія на головній створити',
            'Подія на головній редагувати',
            'Подія на головній видалити',
            'Район список',
            'Район створити',
            'Район редагувати',
            'Район видалити',
            'Користувач статистика',
            'Користувач список',
            'Користувач активувати',
            'Користувач редагувати',
            'Користувач видалити',
            'Батальон створити',
            'Батальон редагувати',
            'Батальон видалити',
            'Рота створити',
            'Рота редагувати',
            'Рота видалити',
            'Взвод створити',
            'Взвод редагувати',
            'Взвод видалити',
            'Оперативна обстановка редагувати',
            'Зворотній зв’язок список',
            'Зворотній зв’язок створити',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Користувач']);
        $role1->givePermissionTo('Подія список');
        $role1->givePermissionTo('Подія створити');
        $role1->givePermissionTo('Район список');
        $role1->givePermissionTo('Зворотній зв’язок створити');

//        TODO: проверить, редактирует ли администратор и пользователь свои події
        $role2 = Role::create(['name' => 'Адміністратор']);
        $role2->givePermissionTo('Подія список');
        $role2->givePermissionTo('Подія створити');
        $role2->givePermissionTo('Район список');
        $role2->givePermissionTo('Користувач статистика');
        $role2->givePermissionTo('Користувач список');
        $role2->givePermissionTo('Користувач активувати');
        $role2->givePermissionTo('Роль список');

        $role3 = Role::create(['name' => 'Супер Адміністратор']);

        $role4 = Role::create(['name' => 'Аналітик']);
        $role4->givePermissionTo('Подія список');
        $role4->givePermissionTo('Подія створити');
        $role4->givePermissionTo('Подія редагувати');
        $role4->givePermissionTo('Подія видалити');
        $role4->givePermissionTo('Район список');
        $role4->givePermissionTo('Оперативна обстановка редагувати');

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // create demo users
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Аналітик',
            'surname' => 'Surname',
            'email' => 'analyst@analyst.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user1->assignRole($role4);
        $user2 = \App\Models\User::factory()->create([
            'name' => 'Супер Адміністратор',
            'surname' => 'Surname',
            'email' => 'superadmin@superadmin.com',
            'is_activated' => 'Так',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user2->assignRole($role3);
        $user3 = \App\Models\User::factory()->create([
            'name' => 'Адміністратор',
            'surname' => 'Адміністратор',
            'email' => 'admin@admin.com',
            'formation_id' => 1,
            'battalion' => 1,
            'company' => 1,
            'platoon' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user3->assignRole($role2);
//        $user = \App\Models\User::factory()->create([
//            'name' => 'Адміністратор2',
//            'surname' => 'Адміністратор2',
//            'email' => 'admin2@admin.com',
//            'formation_id' => 1,
//            'battalion' => 1,
//            'company' => 2,
//            'platoon' => 3,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//        $user->assignRole($role2);
//        $user = \App\Models\User::factory()->create([
//            'name' => 'Адміністратор3',
//            'surname' => 'Адміністратор3',
//            'email' => 'admin3@admin.com',
//            'formation_id' => 1,
//            'battalion' => 1,
//            'company' => 3,
//            'platoon' => 1,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//        $user->assignRole($role2);
        $user4 = \App\Models\User::factory()->create([
            'name' => 'Користувач',
            'surname' => 'Користувач',
            'email' => 'user@user.com',
            'formation_id' => 1,
            'battalion' => 1,
            'company' => 3,
            'platoon' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user4->assignRole($role1);
    }
}

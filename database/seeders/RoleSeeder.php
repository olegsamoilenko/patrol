<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Немає ролі';
        $role->slug = 'none';
        $role->save();

        $role = new Role();
        $role->name = 'Користувач';
        $role->slug = 'user';
        $role->save();

        $role = new Role();
        $role->name = 'Адміністратор';
        $role->slug = 'admin';
        $role->save();

    }
}

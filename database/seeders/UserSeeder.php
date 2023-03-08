<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $role = Role::where('name', 'Користувач')->first();
        User::factory()
            ->count(5)
            ->hasAttached($role)
            ->create()
        ;
    }
}

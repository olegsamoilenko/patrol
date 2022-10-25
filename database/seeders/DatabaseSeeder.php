<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();


        $user = new User();
        $user->name = 'admin';
        $user->surname = 'admin';
        $user->phone = '050111111';
        $user->email = 'admin@admin.com';
        $user->email_verified_at = now();
        $user->password = bcrypt('11111111'); // password
        $user->remember_token = Str::random(10);
        $user->save();
        $user->roles()->attach($adminRole);
        $user->roles()->attach($userRole);
    }
}

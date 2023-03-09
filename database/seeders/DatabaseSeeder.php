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
        $this->call(DistrictSeeder::class);
        $this->call(FormationSeeder::class);
        $this->call(BasicAdminPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(IncidentSeeder::class);
        $this->call(SummarySeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(MessageSeeder::class);
    }
}

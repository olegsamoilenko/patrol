<?php

namespace Database\Seeders;

use App\Models\Formation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Formation::create(['region' => 'Полтавька', 'city' => 'Лубни', 'name' => 'Лубенська МТГ', 'number' => 1]);
    }
}

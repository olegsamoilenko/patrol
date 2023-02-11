<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        District::create(['title' => 'Патруль №: 1', 'streets' => 'Шевченка, Перемоги', 'order' => 1]);
        District::create(['title' => 'Патруль №: 2', 'streets' => 'Шевченка,Собор', 'order' => 2]);
        District::create(['title' => 'Патруль №: 3', 'streets' => 'Перемоги, Собор', 'order' => 3]);
        District::create(['title' => 'Патруль №: 4', 'streets' => 'Володимирська', 'order' => 4]);
        District::create(['title' => 'Патруль №: 5', 'streets' => 'Монастирська, Садова', 'order' => 5]);
        District::create(['title' => 'Патруль №: 6', 'streets' => 'Олександрівська, Фабрична', 'order' => 6]);
        District::create(['title' => 'Патруль №: 7', 'streets' => 'Драгоманова, Толстого', 'order' => 7]);
        District::create(['title' => 'Пост ДАЇ', 'order' => 8]);
        District::create(['title' => 'В місті', 'order' => 9]);
        District::create(['title' => 'Поза містом', 'order' => 10]);
        District::create(['title' => 'Невизначено', 'order' => 11]);

    }
}

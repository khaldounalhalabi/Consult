<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['category_name' => 'Medical']);
        Category::create(['category_name' => 'Career']);
        Category::create(['category_name' => 'Psychology']);
        Category::create(['category_name' => 'Family']);
        Category::create(['category_name' => 'Bussines and Management']);
    }
}

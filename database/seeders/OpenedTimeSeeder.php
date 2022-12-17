<?php

namespace Database\Seeders;

use App\Models\OpenedTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpenedTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OpenedTime::factory()->count(4)->create();
    }
}

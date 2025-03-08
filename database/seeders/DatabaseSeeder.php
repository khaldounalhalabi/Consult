<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        $this->call([
            AppointmentSeeder::class,
            CategorySeeder::class,
            CommentReviewSeeder::class,
            ExpertSeeder::class,
            FavoriteSeeder::class,
            MessageSeeder::class,
            OpenedTimeSeeder::class,
            UserSeeder::class,
        ]);
    }
}

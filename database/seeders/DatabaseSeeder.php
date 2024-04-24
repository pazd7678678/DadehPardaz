<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Pzamani\Auth\database\seeders\SessionSeeder;
use Pzamani\Auth\database\seeders\UserSeeder;
use Pzamani\Payment\database\seeders\PaytypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SessionSeeder::class,
            PaytypeSeeder::class,
        ]);
    }
}

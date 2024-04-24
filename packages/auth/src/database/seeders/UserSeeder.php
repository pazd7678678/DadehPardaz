<?php

namespace Pzamani\Auth\database\seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $password = Hash::make('123456');
            DB::table('users')->insert([
                'id'           => '00000000-0000-0000-0000-000000000000',
                'mobile'       => '123456789',
                'password'     => $password,
                'email'        => 'parisazamanid2022@gmail.com',
                'name'         => 'Parisa',
                'family'       => 'Zamani',
                'nationalcode' => '1234567890',
                'is_active'    => 1,
            ]);
            $faker = Factory::create(config('app.locale'));
            for ($i = 1; $i < 100; $i++) {
                DB::table('users')->insert([
                    'id'           => $faker->unique()->uuid(),
                    'mobile'       => $faker->unique()->regexify('^[0-39][0-9]{8}$'),
                    'password'     => $password,
                    'email'        => $faker->unique()->email,
                    'name'         => $faker->name,
                    'family'       => $faker->lastName,
                    'nationalcode' => str_shuffle('1234567890'),
                    'is_active'    => 1,
                ]);
            }
        });
    }
}

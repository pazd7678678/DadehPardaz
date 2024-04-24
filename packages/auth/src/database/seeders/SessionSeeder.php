<?php

namespace Pzamani\Auth\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sessions')->insert([
            'id'       => '00000000-0000-0000-0000-000000000000',
            'user_id'  => '00000000-0000-0000-0000-000000000000',
            'token'    => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsImlhdCI6MTcxMzk1ODMwMC44Njk1ODEsIm5iZiI6MTcxMzk1ODMwMC44Njk1ODEsImV4cCI6MTcxNjU1MDMwMC44Njk1ODEsImp0aSI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCIsInN1YiI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCJ9.3H6mwO9R7rP8NJw-hAmDWDD35OYT9BMvWnaHV-sbf_s',
            'refresh'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsImlhdCI6MTcxMzk1ODI1NC41MDczOTIsIm5iZiI6MTcxMzk1ODI1NC41MDczOTIsImV4cCI6MTc0NTQ5NDI1NC41MDczOTIsImp0aSI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCIsInN1YiI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCJ9.myMe-9nK6mn7ALBAfzSjI6Crn_Ic-6mcZMH16z-i-WQ',
            'ip'       => '0.0.0.0',
            'device'   => 'Seeder',
            'login_at' => now(),
        ]);
    }
}

<?php

namespace Pzamani\Payment\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaytypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $paytypes = [
                ['name' => 'Freight'],
                ['name' => 'Transportation'],
                ['name' => 'Equipment'],
            ];
            foreach ($paytypes as $paytype) {
                DB::table('paytypes')->insert($paytype);
            }
        });
    }
}

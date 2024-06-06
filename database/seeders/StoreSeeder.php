<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 20; $i++) { 
            DB::table('stores')->insert([
                'id' => $i + 1,
                'name' => Str::random(10),
                'user_id'=> rand(1, 10),
            ]);
        }
    }
}

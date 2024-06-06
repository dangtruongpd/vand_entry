<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 30; $i++) { 
            $name = Str::random(10);
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $name,
                'store_id'=> rand(1, 20),
                'price'=> rand(1, 1000000),
            ]);
        }
    }
}

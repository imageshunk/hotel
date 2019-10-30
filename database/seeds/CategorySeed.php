<?php

use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'category' => 'Food'
        ]);
        DB::table('categories')->insert([
            'category' => 'Beverages'
        ]);
    }
}

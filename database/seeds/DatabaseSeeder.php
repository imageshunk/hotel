<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentTypes::class);
        $this->call(CategorySeed::class);
        $this->call(AdminSeed::class);
    }
}

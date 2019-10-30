<?php

use Illuminate\Database\Seeder;

class PaymentTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->insert([
            'payment_type' => 'Cash'
        ]);
        DB::table('payment_types')->insert([
            'payment_type' => 'Credit Card'
        ]);
        DB::table('payment_types')->insert([
            'payment_type' => 'Debit Card'
        ]);
        DB::table('payment_types')->insert([
            'payment_type' => 'Mobile Money'
        ]);
    }
}

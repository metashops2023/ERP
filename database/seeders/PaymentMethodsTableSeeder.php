<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'created_at' => NULL,
                'id' => 1,
                'is_fixed' => 1,
                'name' => 'Cash',
                'updated_at' => '2022-01-06 10:11:04',
            ),
            1 => 
            array (
                'created_at' => NULL,
                'id' => 2,
                'is_fixed' => 1,
                'name' => 'Debit-Card',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'created_at' => NULL,
                'id' => 3,
                'is_fixed' => 1,
                'name' => 'Credit-Card',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'created_at' => NULL,
                'id' => 4,
                'is_fixed' => 1,
                'name' => 'Bank-Transfer',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'created_at' => NULL,
                'id' => 5,
                'is_fixed' => 1,
                'name' => 'Cheque',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
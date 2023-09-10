<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_method_settings')->delete();
        
        \DB::table('payment_method_settings')->insert(array (
            0 => 
            array (
                'account_id' => NULL,
                'branch_id' => NULL,
                'created_at' => '2022-04-21 13:05:38',
                'id' => 1,
                'payment_method_id' => 1,
                'updated_at' => '2022-04-21 13:05:38',
            ),
            1 => 
            array (
                'account_id' => 1,
                'branch_id' => NULL,
                'created_at' => '2022-04-21 13:05:38',
                'id' => 2,
                'payment_method_id' => 2,
                'updated_at' => '2022-04-21 13:05:38',
            ),
            2 => 
            array (
                'account_id' => NULL,
                'branch_id' => NULL,
                'created_at' => '2022-04-21 13:05:38',
                'id' => 3,
                'payment_method_id' => 3,
                'updated_at' => '2022-04-21 13:05:38',
            ),
            3 => 
            array (
                'account_id' => 1,
                'branch_id' => NULL,
                'created_at' => '2022-04-21 13:05:38',
                'id' => 4,
                'payment_method_id' => 4,
                'updated_at' => '2022-04-21 13:05:38',
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoiceSchemasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('invoice_schemas')->delete();
        
        \DB::table('invoice_schemas')->insert(array (
            0 => 
            array (
                'created_at' => '2023-02-23 16:19:09',
                'format' => '2',
                'id' => 1,
                'is_default' => 1,
                'name' => 'Inv',
                'number_of_digit' => NULL,
                'prefix' => '2023/',
                'start_from' => '0',
                'updated_at' => '2023-02-23 16:19:09',
            ),
        ));
        
        
    }
}
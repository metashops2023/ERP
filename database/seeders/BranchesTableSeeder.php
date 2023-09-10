<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branches')->delete();
        
        \DB::table('branches')->insert(array (
            0 => 
            array (
                'add_sale_invoice_layout_id' => 1,
                'after_purchase_store' => NULL,
                'alternate_phone_number' => NULL,
                'branch_code' => 'MB1',
                'city' => 'Cairo',
                'country' => 'Egypt',
                'created_at' => NULL,
                'default_account_id' => NULL,
                'email' => NULL,
                'id' => 1,
                'invoice_schema_id' => 1,
                'logo' => 'default.png',
                'name' => 'Main Branch',
                'phone' => '123456789',
                'pos_sale_invoice_layout_id' => 1,
                'purchase_permission' => 1,
                'state' => 'Heliopolis',
                'updated_at' => NULL,
                'website' => NULL,
                'zip_code' => '54321',
            ),
        ));
        
        
    }
}
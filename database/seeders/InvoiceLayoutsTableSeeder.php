<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoiceLayoutsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('invoice_layouts')->delete();
        
        \DB::table('invoice_layouts')->insert(array (
            0 => 
            array (
                'account_name' => NULL,
                'account_no' => NULL,
                'bank_branch' => NULL,
                'bank_name' => NULL,
                'branch_alternate_number' => 1,
                'branch_city' => 1,
                'branch_country' => 0,
                'branch_email' => 1,
                'branch_landmark' => 0,
                'branch_phone' => 1,
                'branch_state' => 1,
                'branch_zipcode' => 1,
                'challan_heading' => 'Dft',
                'created_at' => '2023-02-23 16:20:04',
                'customer_address' => 1,
                'customer_name' => 1,
                'customer_phone' => 1,
                'customer_tax_no' => 1,
                'draft_heading' => 'Draft',
                'footer_text' => NULL,
                'gap_from_top' => NULL,
                'header_text' => NULL,
                'id' => 1,
                'invoice_heading' => 'Invoice',
                'invoice_notice' => NULL,
                'is_default' => 1,
                'is_header_less' => 0,
                'layout_design' => 1,
                'name' => 'Inv',
                'product_brand' => 0,
                'product_cate' => 0,
                'product_discount' => 1,
                'product_imei' => 0,
                'product_img' => 0,
                'product_price_exc_tax' => 0,
                'product_price_inc_tax' => 0,
                'product_tax' => 1,
                'product_w_discription' => 0,
                'product_w_duration' => 1,
                'product_w_type' => 1,
                'quotation_heading' => 'Inv',
                'sale_note' => 0,
                'show_seller_info' => 1,
                'show_shop_logo' => 1,
                'show_total_in_word' => 1,
                'sub_heading_1' => NULL,
                'sub_heading_2' => NULL,
                'sub_heading_3' => NULL,
                'updated_at' => '2023-02-23 16:20:04',
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addons = array(
            array('branches' => '1', 'hrm' => '1', 'todo' => '1', 'service' => '0', 'manufacturing' => '1', 'e_commerce' => '0', 'cash_counter_limit' => '999', 'branch_limit' => '999')
        );

        DB::table("addons")->insert($addons);
    }
}

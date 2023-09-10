<?php

namespace Database\Seeders;

use Exception;
use App\Models\AdminAndUser;
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
        try {
            echo "Seeding Default Data" . PHP_EOL;


            
            $this->call(GeneralSettingsSeeder::class);

            $this->call(AddonsSeeder::class);
            $this->call(ShortMenusSeeder::class);
            $this->call(PosShortMenusSeeder::class);
            $this->call(CurrenciesTableSeeder::class);
            $this->call(InvoiceLayoutsTableSeeder::class);
            $this->call(BranchesTableSeeder::class);
            $this->call(AccountsTableSeeder::class);
            $this->call(PaymentMethodsTableSeeder::class);
            $this->call(PaymentMethodSettingsTableSeeder::class);
            $this->call(InvoiceSchemasTableSeeder::class);


            $user = AdminAndUser::create([
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@metashops.com.sa',
                'username' => 'superadmin',
                'password' => bcrypt('12345'),
                'gender' => 'Male',
                'photo' => 'default.png',
                'allow_login' => 1,
                'role_type' => 1,
                'role_permission_id' => 1,
                'branch_id' => 1
            ]);

        $this->call(AccountBranchesTableSeeder::class);
    } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}

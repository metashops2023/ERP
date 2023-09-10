<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosShortMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pos_short_menus = array(
            array('id' => '1','url' => 'product.categories.index','name' => 'Categories','icon' => 'fas fa-th-large','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '2','url' => 'product.subcategories.index','name' => 'SubCategories','icon' => 'fas fa-code-branch','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '3','url' => 'product.brands.index','name' => 'Brands','icon' => 'fas fa-band-aid','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '4','url' => 'products.all.product','name' => 'Product List','icon' => 'fas fa-sitemap','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '5','url' => 'products.add.view','name' => 'Add Product','icon' => 'fas fa-plus-circle','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '6','url' => 'product.variants.index','name' => 'Variants','icon' => 'fas fa-align-center','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '7','url' => 'product.import.create','name' => 'Import Products','icon' => 'fas fa-file-import','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '8','url' => 'product.selling.price.groups.index','name' => 'Price Group','icon' => 'fas fa-layer-group','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '9','url' => 'barcode.index','name' => 'G.Barcode','icon' => 'fas fa-barcode','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '10','url' => 'product.warranties.index','name' => 'Warranties ','icon' => 'fas fa-shield-alt','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '11','url' => 'contacts.supplier.index','name' => 'Suppliers','icon' => 'fas fa-address-card','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '12','url' => 'contacts.suppliers.import.create','name' => 'Import Suppliers','icon' => 'fas fa-file-import','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '13','url' => 'contacts.customer.index','name' => 'Customers','icon' => 'far fa-address-card','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '14','url' => 'contacts.customers.import.create','name' => 'Import Customers','icon' => 'fas fa-file-upload','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '15','url' => 'purchases.create','name' => 'Add Purchase','icon' => 'fas fa-shopping-cart','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '16','url' => 'purchases.index_v2','name' => 'Purchase List','icon' => 'fas fa-list','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '17','url' => 'purchases.returns.index','name' => 'Purchase Return','icon' => 'fas fa-undo','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '18','url' => 'sales.store','name' => 'Add Sale','icon' => 'fas fa-cart-plus','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '19','url' => 'sales.index2','name' => 'Add Sale List','icon' => 'fas fa-tasks','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '20','url' => 'sales.pos.create','name' => 'POS','icon' => 'fas fa-cash-register','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '21','url' => 'sales.pos.list','name' => 'POS List','icon' => 'fas fa-tasks','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '22','url' => 'sales.drafts','name' => 'Draft List','icon' => 'fas fa-drafting-compass','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '23','url' => 'sales.quotations','name' => 'Quotation List','icon' => 'fas fa-quote-right','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '24','url' => 'sales.returns.index','name' => 'Sale Returns','icon' => 'fas fa-undo','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '25','url' => 'sales.shipments','name' => 'Shipments','icon' => 'fas fa-shipping-fast','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '26','url' => 'expenses.create','name' => 'Add Expense','icon' => 'fas fa-plus-square','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '27','url' => 'expenses.index','name' => 'Expense List','icon' => 'far fa-list-alt','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '28','url' => 'expenses.categories.index','name' => 'Expense Categories Categories','icon' => 'fas fa-cubes','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '29','url' => 'users.create','name' => 'Add User','icon' => 'fas fa-user-plus','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '30','url' => 'users.index','name' => 'User List','icon' => 'fas fa-list-ol','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '31','url' => 'users.role.create','name' => 'Add Role','icon' => 'fas fa-plus-circle','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '32','url' => 'users.role.index','name' => 'Role List','icon' => 'fas fa-th-list','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '33','url' => 'accounting.banks.index','name' => 'Bank','icon' => 'fas fa-university','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '34','url' => 'accounting.types.index','name' => 'Account Types','icon' => 'fas fa-th','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '35','url' => 'accounting.accounts.index','name' => 'Accounts','icon' => 'fas fa-th','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '36','url' => 'accounting.assets.index','name' => 'Assets','icon' => 'fas fa-luggage-cart','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '37','url' => 'accounting.balance.sheet','name' => 'Balance Sheet','icon' => 'fas fa-balance-scale','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '38','url' => 'accounting.trial.balance','name' => 'Trial Balance','icon' => 'fas fa-balance-scale-right','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '39','url' => 'accounting.cash.flow','name' => 'Cash Flow','icon' => 'fas fa-money-bill-wave','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '40','url' => 'settings.general.index','name' => 'General Settings','icon' => 'fas fa-cogs','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '41','url' => 'settings.taxes.index','name' => 'Taxes','icon' => 'fas fa-percentage','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '42','url' => 'invoices.schemas.index','name' => 'Invoice Schemas','icon' => 'fas fa-file-invoice-dollar','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '43','url' => 'invoices.layouts.index','name' => 'Invoice Layouts','icon' => 'fas fa-file-invoice','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '44','url' => 'settings.barcode.index','name' => 'Barcode Settings','icon' => 'fas fa-barcode','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00'),
            array('id' => '45','url' => 'settings.cash.counter.index','name' => 'Cash Counter','icon' => 'fas fa-store','created_at' => '2021-08-21 15:41:00','updated_at' => '2021-08-21 15:41:00')
          );

          DB::table('pos_short_menus')->insert($pos_short_menus);
    }
}

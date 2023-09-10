<?php

namespace App\Utils;

use App\Utils\Converter;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\DB;

class UserActivityLogUtil
{
    public function subjectTypes()
    {
        return [
            26 => 'Product',
            1 => 'Customers',
            2 => 'Suppliers',
            3 => 'Users',
            18 => 'User Login',
            19 => 'User Logout',
            27 => 'Receive Payment',
            28 => 'Payment',
            4 => 'Purchase',
            5 => 'Purchase Order',
            6 => 'Purchase Return',
            7 => 'Sales',
            8 => 'Sales Order',
            9 => 'Sale Return',
            20 => 'POS Sale',
            10 => 'Transfer B.Location To Warehouse',
            11 => 'Transfer Warehouse To B.Location',
            12 => 'Transfer B.Location To B.Location',
            13 => 'Stock Adjustment From B.Location',
            14 => 'Stock Adjustment From Warehouse',
            15 => 'Expense',
            16 => 'Bank',
            17 => 'Accounts',
            20 => 'Categories',
            21 => 'Sub-Categories',
            22 => 'Brands',
            23 => 'Units',
            24 => 'Variants',
            25 => 'Warranties',
        ];
    }

    public function actions()
    {
        return [
            1 => 'Added',
            2 => 'Updated',
            3 => 'Deleted',
            4 => 'User Login',
            5 => 'User Logout',
        ];
    }

    public function descriptionModel()
    {
        return [
            1 => [ // Customers
                'fields' => [
                    'name',
                    'phone',
                    'contact_id',
                    'total_sale_due',
                ],
                'texts' => [
                    'Name : ',
                    'Phone : ',
                    'Customer ID : ',
                    'Balance Due : ',
                ]
            ],
            2 => [ // Suppliers
                'fields' => [
                    'name',
                    'phone',
                    'contact_id',
                    'total_sale_due',
                ],
                'texts' => [
                    'Name : ',
                    'Phone : ',
                    'Supplier ID : ',
                    'Balance Due : ',
                ]
            ],
            3 => [ // Users
                'fields' => [
                    'prefix',
                    'name',
                    'last_name',
                    'username',
                ],
                'texts' => [
                    'prefix : ',
                    'Name : ',
                    'Last Lame : ',
                    'Username',
                ]
            ],
            4 => [ // Purchase
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_purchase_amount',
                    'paid',
                    'due',
                ],
                'texts' => [
                    'Date : ',
                    'P.Invoice ID : ',
                    'Total Purchase Amount : ',
                    'Paid : ',
                    'Due : ',
                ]
            ],
            5 => [ // Purchase Order
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_purchase_amount',
                    'paid',
                    'due',
                ],
                'texts' => [
                    'Order Date : ',
                    'Purchase Order ID : ',
                    'Total Ordered Amt : ',
                    'Paid : ',
                    'Due : ',
                ]
            ],
            6 => [ // Purchase Return
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_return_amount',
                    'total_return_due_received',
                ],
                'texts' => [
                    'Date : ',
                    'Return Invoice ID : ',
                    'Total Returned Amt : ',
                    'Refunded Amt : ',
                ]
            ],
            7 => [ // Sales
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_payable_amount',
                    'paid',
                    'due',
                ],
                'texts' => [
                    'Date : ',
                    'Invoice ID : ',
                    'Total Payable Amount : ',
                    'Paid : ',
                    'Due : ',
                ]
            ],
            8 => [ // Sales Order
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_payable_amount',
                    'paid',
                    'due',
                ],
                'texts' => [
                    'Date : ',
                    'Order ID : ',
                    'Total Payable Amt',
                    'Paid',
                    'Due',
                ]
            ],
            9 => [ // Sales Return
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_return_amount',
                    'total_return_due_pay',
                ],
                'texts' => [
                    'Date : ',
                    'Return Invoice ID : ',
                    'Total Returned Amt. : ',
                    'Refunded Amt. : ',
                ]
            ],
            10 => [ // Transfer B.Location To Warehouse
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_send_qty',
                    'total_received_qty',
                ],
                'texts' => [
                    'Date : ',
                    'Reference ID : ',
                    'Total Send Quantity : ',
                    'Total Received Quantity : ',
                ]
            ],
            11 => [ // Transfer Warehouse To B.Location
                'fields' => [
                    'date',
                    'invoice_id',
                    'total_send_qty',
                    'total_received_qty',
                ],
                'texts' => [
                    'Date : ',
                    'Reference ID : ',
                    'Total Send Quantity : ',
                    'Total Received Quantity : ',
                ]
            ],
            12 => [ // Transfer B.Location To Warehouse
                'fields' => [
                    'date',
                    'ref_id',
                    'total_send_qty',
                    'total_received_qty',
                ],
                'texts' => [
                    'Date : ',
                    'Reference ID : ',
                    'Total Send Quantity : ',
                    'Total Received Quantity : ',
                ]
            ],
            13 => [ // Stock Adjustment From B.Location
                'fields' => [
                    'date',
                    'invoice_id',
                    'net_total_amount',
                    'recovered_amount',
                ],
                'texts' => [
                    'Date : ',
                    'Reference ID : ',
                    'Total Adjusted Amt. : ',
                    'Total Recovered Amount : ',
                ]
            ],
            14 => [ // Stock Adjustment From Warehouse
                'fields' => [
                    'date',
                    'invoice_id',
                    'net_total_amount',
                    'recovered_amount',
                ],
                'texts' => [
                    'Date : ',
                    'Reference ID : ',
                    'Total Adjusted Amt. : ',
                    'Total Recovered Amount : ',
                ]
            ],
            15 => [ // Expenses
                'fields' => [
                    'date',
                    'invoice_id',
                    'net_total_amount'
                ],
                'texts' => [
                    'Date : ',
                    'Expense Voucher No : ',
                    'Net Total Amt. : ',
                ]
            ],
            16 => [ // Bank
                'fields' => [
                    'name',
                ],
                'texts' => [
                    'Bank Name : ',
                ]
            ],
            17 => [ // Accounts
                'fields' => [
                    'name',
                    'account_number',
                    'opening_balance',
                    'balance',
                ],
                'texts' => [
                    'Account Name : ',
                    'Account Number : ',
                    'Opening Balance : ',
                    'Balance : ',
                ]
            ],
            18 => [ // User login
                'fields' => [
                    'username',
                ],
                'texts' => [
                    'Username : '
                ]
            ],
            19 => [ // User Logout
                'fields' => [
                    'username',
                ],
                'texts' => [
                    'Username : '
                ]
            ],
            20 => [ // Categories
                'fields' => [
                    'id',
                    'name',
                ],
                'texts' => [
                    'Category ID : ',
                    'Category Name : ',
                ]
            ],
            21 => [ // Sub-Categories
                'fields' => [
                    'id',
                    'name',
                ],
                'texts' => [
                    'Sub-Category ID : ',
                    'Sub-Category Name : ',
                ]
            ],
            22 => [ // Brands
                'fields' => [
                    'id',
                    'name',
                ],
                'texts' => [
                    'Brand ID: ',
                    'Brand Name : ',
                ]
            ],
            23 => [ // UNITS
                'fields' => [
                    'name',
                    'code_name',
                ],
                'texts' => [
                    'Unit Name : ',
                    'Short Name : ',
                ]
            ],
            24 => [ // Variants
                'fields' => [
                    'id',
                    'bulk_variant_name',
                ],
                'texts' => [
                    'ID : ',
                    'Variant Name : ',
                ]
            ],
            25 => [ // Warranties
                'fields' => [
                    'name',
                    'duration',
                    'duration_type',
                ],
                'texts' => [
                    'Warranty Name : ',
                    'Duration : ',
                    'Duration Type : ',
                ]
            ],
            26 => [ // Product
                'fields' => [
                    'name',
                    'product_code',
                    'product_cost_with_tax',
                    'product_price',
                ],
                'texts' => [
                    'Name : ',
                    'P.Code(SKU) : ',
                    'Cost.inc Tax : ',
                    'Price.Exc Tax : ',
                ]
            ],
            27 => [ // Receive Payment
                'fields' => [
                    'date',
                    'voucher_no',
                    'ags',
                    'customer',
                    'phone',
                    'method',
                    'paid_amount',
                ],
                'texts' => [
                    'Date : ',
                    'Voucher : ',
                    'AGS : ',
                    'Customer : ',
                    'Phn No : ',
                    'Type : ',
                    'Paid : ',
                ]
            ],
            28 => [ // Payment
                'fields' => [
                    'date',
                    'voucher_no',
                    'agp',
                    'supplier',
                    'phone',
                    'method',
                    'paid_amount',
                ],
                'texts' => [
                    'Date : ',
                    'Voucher : ',
                    'AGP : ',
                    'Supplier : ',
                    'Phn No : ',
                    'Type : ',
                    'Paid : ',
                ]
            ],
        ];
    }

    public function addLog($action, $subject_type, $data_obj, $branch_id = NULL, $user_id = NULL)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();

        $dateFormat = json_decode($generalSettings->business, true)['date_format'];

        $__dateFormat = str_replace('y', 'Y', $dateFormat);

        $descriptionModel = $this->descriptionModel();
        $addLog = new UserActivityLog();
        $addLog->branch_id = $branch_id ? $branch_id : auth()->user()->branch_id;
        $addLog->user_id = $user_id ? $user_id : auth()->user()->id;
        $addLog->action = $action;
        $addLog->subject_type = $subject_type;
        $addLog->date = date($__dateFormat);
        $addLog->report_date = date('Y-m-d H:i:s');

        // prepare the descriptions
        $description = '';

        $index = 0;
        foreach ($descriptionModel[$subject_type]['fields'] as $field) {

            $description .= $descriptionModel[$subject_type]['texts'][$index] . (isset($data_obj->{$field}) ? $data_obj->{$field} : 'N/A' ) . ', ';
            $index++;
        }

        $addLog->descriptions = $description;
        $addLog->save();
    }
}

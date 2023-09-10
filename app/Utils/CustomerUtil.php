<?php

namespace App\Utils;

use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerUtil
{
    public function customerListTable($request)
    {
        $branchWiseCustomerAmountUtil =  new \App\Utils\BranchWiseCustomerAmountUtil();

        if (auth()->user()->role_type == 1) {
            if (isset($request->branch_id) && $request->branch_id != null) {
                $customers = DB::table('customers')
                    ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                    ->select(
                        'customers.id',
                        'customers.contact_id',
                        'customers.name',
                        'customers.business_name',
                        'customers.status',
                        'customers.phone',
                        'customer_groups.group_name'
                    )->where('customers.branch_id', $request->branch_id);
            } else {
                $customers = DB::table('customers')
                    ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                    ->select(
                        'customers.id',
                        'customers.contact_id',
                        'customers.name',
                        'customers.business_name',
                        'customers.status',
                        'customers.phone',
                        'customer_groups.group_name'
                    );
            }
        } else if (auth()->user()->role_type == 2) {
            if (isset($request->branch_id) && $request->branch_id != null) {
                $customers = DB::table('customers')
                    ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                    ->select(
                        'customers.id',
                        'customers.contact_id',
                        'customers.name',
                        'customers.business_name',
                        'customers.status',
                        'customers.phone',
                        'customer_groups.group_name'
                    )->where('customers.admin_user_id', auth()->user()->id)
                    ->where('customers.branch_id', $request->branch_id);
            } else {
                $customers = DB::table('customers')
                    ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                    ->select(
                        'customers.id',
                        'customers.contact_id',
                        'customers.name',
                        'customers.business_name',
                        'customers.status',
                        'customers.phone',
                        'customer_groups.group_name'
                    )->where('customers.admin_user_id', auth()->user()->id);
            }
        } else {
            $customers = DB::table('customers')
                ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                ->select(
                    'customers.id',
                    'customers.contact_id',
                    'customers.name',
                    'customers.business_name',
                    'customers.status',
                    'customers.phone',
                    'customer_groups.group_name'
                )->where('customers.admin_user_id', auth()->user()->id);
        }

        return DataTables::of($customers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';

                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . route('contacts.customer.view', [$row->id]) . '"><i class="fas fa-tasks text-primary"></i>'.__("Manage").' </a>';

                    $html .= '<a class="dropdown-item" id="money_receipt_list" href="' . route('money.receipt.voucher.list', [$row->id]) . '"><i class="far fa-file-alt text-primary"></i> '.__("Payment Receipt Voucher").'</a>';

                    if (auth()->user()->permission->contact['customer_edit'] == '1') {

                        $html .= '<a class="dropdown-item" href="' . route('contacts.customer.edit', [$row->id]) . '" id="edit"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                    }

                    if (auth()->user()->permission->contact['customer_delete'] == '1') {

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('contacts.customer.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> '.__("Delete").'</a>';
                    }

                    if ($row->status == 1) :

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.customer.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i> '.__("Change Status").'</a>';
                    else :

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.customer.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i>'.__("Change Status").' </a>';
                    endif;




                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('business_name', fn ($row) => $row->business_name ? $row->business_name : '...')

            ->editColumn('group_name', fn ($row) => $row->group_name ? $row->group_name : '...')

            ->editColumn('credit_limit', function ($row) use ($request) {

                if ($request->branch_id == '') {

                    return '...';
                } else {

                    $branch_id = $request->branch_id == 'NULL' ? NULL : $request->branch_id;
                    $creditLimit = DB::table('customer_credit_limits')->where('branch_id', $branch_id)->where('customer_id', $row->id)->first(['credit_limit']);
                    return $creditLimit ? $creditLimit->credit_limit : '';
                }
            })

            ->editColumn('opening_balance', function ($row) use ($request, $branchWiseCustomerAmountUtil) {
                $openingBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['opening_balance'];
                return '<span class="opening_balance" data-value="' . $openingBalance . '">' . \App\Utils\Converter::format_in_bdt($openingBalance) . '</span>';
            })

            ->editColumn('total_sale', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                $totalSale = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale'];
                return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
            })

            ->editColumn('total_paid', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_paid'];
                return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';
            })

            ->editColumn('total_sale_due', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                $totalSaleDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale_due'];
                return '<span class="total_sale_due" data-value="' . $totalSaleDue . '">' . \App\Utils\Converter::format_in_bdt($totalSaleDue) . '</span>';
            })

            ->editColumn('total_return', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                $totalReturn = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_return'];
                return '<span class="total_return" data-value="' . $totalReturn . '">' . \App\Utils\Converter::format_in_bdt($totalReturn) . '</span>';
            })

            ->editColumn('total_sale_return_due', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                $totalSaleReturnDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale_return_due'];
                return '<span class="total_sale_return_due" data-value="' . $totalSaleReturnDue . '">' . \App\Utils\Converter::format_in_bdt($totalSaleReturnDue) . '</span>';
            })

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    return '<span class="text-success">Active</span>';
                } else {

                    return '<span class="text-danger">Inactive</span>';
                }
            })
            ->filter(function($query) use($request){
                // dd($request->active);
                if($request->active=="false"){
                    $query->where('status',1);
                }else{
                $query->where('status',0);
            }
               })
            ->rawColumns(['action', 'credit_limit', 'business_name', 'group_name', 'opening_balance', 'total_sale', 'total_paid', 'total_sale_due', 'total_return', 'total_sale_return_due', 'status'])
            ->make(true);
    }

    // public function adjustCustomerAmountForSalePaymentDue($customerId)
    // {
    //     $customer = Customer::where('id', $customerId)->first();

    //     $totalCustomerSale = DB::table('sales')->where('customer_id', $customerId)
    //         ->whereIn('sales.status', [1, 3])
    //         ->select(DB::raw('sum(total_payable_amount) as total_sale'))
    //         ->groupBy('customer_id')->get();

    //     $totalCustomerPayment = DB::table('customer_payments')
    //         ->select(
    //             DB::raw('sum(paid_amount) as c_paid'),
    //             DB::raw('sum(less_amount) as less')
    //         )->where('customer_id', $customerId)
    //         ->where('type', 1)
    //         ->groupBy('customer_id')->get();

    //     $totalSalePayment = DB::table('sale_payments')
    //         ->where('sale_payments.customer_payment_id', NULL)
    //         ->where('sale_payments.payment_type', 1)
    //         ->where('sale_payments.customer_id', $customerId)->select(DB::raw('sum(paid_amount) as s_paid'))
    //         ->groupBy('sale_payments.customer_id')->get();

    //     $totalSaleReturn = DB::table('sale_returns')
    //         ->where('sale_returns.customer_id', $customerId)
    //         ->select(DB::raw('sum(total_return_amount) as total_return_amt'))
    //         ->groupBy('sale_returns.customer_id')->get();

    //     $totalInvoiceReturnPayment = DB::table('sale_payments') // Paid on invoice return due.
    //         ->where('sale_payments.customer_payment_id', NULL)
    //         ->where('sale_payments.payment_type', 2)
    //         ->where('sale_payments.customer_id', $customerId)
    //         ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
    //         ->groupBy('sale_payments.customer_id')->get();

    //     $totalCustomerReturnPayment = DB::table('customer_payments') // Paid on Total customer return due.
    //         ->where('customer_id', $customerId)
    //         ->where('type', 2)
    //         ->select(DB::raw('sum(paid_amount) as cr_paid'))
    //         ->groupBy('customer_id')->get();

    //     $totalSale = $totalCustomerSale->sum('total_sale');
    //     $totalPaid = $totalCustomerPayment->sum('c_paid') + $totalSalePayment->sum('s_paid');
    //     $totalLess = $totalCustomerPayment->sum('less');
    //     $totalReturn = $totalSaleReturn->sum('total_return_amt');
    //     $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid') + $totalCustomerReturnPayment->sum('cr_paid');

    //     $totalDue = ($totalSale + $customer->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn - $totalLess;

    //     $totalReturnDue = $totalReturn - ($totalSale + $customer->opening_balance - $totalPaid) - $totalReturnPaid;

    //     $customer->total_sale = $totalSale;
    //     $customer->total_paid = $totalPaid;
    //     $customer->total_less = $totalLess;
    //     $customer->total_sale_due = $totalDue;
    //     $customer->total_return = $totalReturn;
    //     $customer->total_sale_return_due = $totalReturnDue > 0 ? $totalReturnDue : 0;;
    //     $customer->save();
    //     return $totalDue;
    // }

    public function adjustCustomerAmountForSalePaymentDue($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();

        $amounts = DB::table('customer_ledgers')
            ->where('customer_ledgers.customer_id', $customerId)->select('voucher_type', DB::raw('SUM(amount) as amt'))
            ->groupBy('customer_ledgers.voucher_type')->get();

        $openingBalance = 0;
        $totalSaleAndOrder = 0;
        $totalPaid = 0;
        $totalReturn = 0;
        $totalLess = 0;
        $totalRefund = 0;

        foreach ($amounts as $amount) {

            if ($amount->voucher_type == 0) {

                $openingBalance += $amount->amt;
            } elseif ($amount->voucher_type == 1) {

                $totalSaleAndOrder += $amount->amt;
            } elseif ($amount->voucher_type == 2) {

                $totalReturn += $amount->amt;
            } elseif ($amount->voucher_type == 3) {

                $totalPaid += $amount->amt;
            } elseif ($amount->voucher_type == 4) {

                $totalRefund += $amount->amt;
            } elseif ($amount->voucher_type == 5) {

                $totalPaid += $amount->amt;
            } elseif ($amount->voucher_type == 6) {

                $totalRefund += $amount->amt;
            }
        }

        $totalDue = ($totalSaleAndOrder + $openingBalance + $totalRefund) - $totalPaid - $totalReturn - $totalLess;

        $totalReturnDue = $totalReturn - ($totalSaleAndOrder + $openingBalance - $totalPaid) - $totalRefund;

        $customer->total_sale = $totalSaleAndOrder;
        $customer->total_paid = $totalPaid;
        $customer->total_less = $totalLess;
        $customer->total_sale_due = $totalDue;
        $customer->total_return = $totalReturn;
        $customer->total_sale_return_due = $totalReturnDue > 0 ? $totalReturnDue : 0;
        $customer->save();

        return $totalDue;
    }

    public static function voucherTypes()
    {
        return [
            1 => 'Sale',
            2 => 'Sale Return',
            3 => 'Received Payment',
            4 => 'Return Payment',
            5 => 'Receive From Customer',
            6 => 'Paid Return Amt.',
        ];
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => [
                'name' => 'Opening Balance',
                'id' => 'sale_id',
                'voucher_no' =>
                'sale_inv_id',
                'amt' => 'debit',
                'par' => 'sale_par',
            ],
            1 => [
                'name' => 'Sale',
                'id' => 'sale_id',
                'voucher_no' => 'sale_inv_id',
                'amt' => 'debit',
                'par' => 'sale_par',
            ],
            2 => [
                'name' => 'Sale Return',
                'id' => 'sale_return_id',
                'voucher_no' => 'return_inv_id',
                'amt' => 'credit',
                'par' => 'sale_return_par',
            ],
            3 => [
                'name' => 'Received Payment',  // invoice wise Payment
                'id' => 'sale_payment_id',
                'voucher_no' => 'sale_payment_voucher',
                'amt' => 'credit',
                'par' => 'sale_payment_par',
            ],
            4 => [
                'name' => 'Paid Return Amt.', // Customer wise Return Payment
                'id' => 'sale_payment_id',
                'voucher_no' => 'sale_payment_voucher',
                'amt' => 'debit',
                'par' => 'sale_payment_par',
            ],
            5 => [
                'name' => 'Received Payment', // Customer wise Payment
                'id' => 'customer_payment_id',
                'voucher_no' => 'customer_payment_voucher',
                'amt' => 'credit',
                'par' => 'customer_payment_par',
            ],
            6 => [
                'name' => 'Paid Return Amt.', // Sale/ Sale Return invoice wise Return Payment
                'id' => 'customer_payment_id',
                'voucher_no' => 'customer_payment_voucher',
                'amt' => 'debit',
                'par' => 'customer_payment_par',
            ],
        ];

        return $data[$voucher_type_id];
    }

    public function addCustomerLedger($voucher_type_id, $customer_id, $branch_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);
        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->branch_id = $branch_id;
        $addCustomerLedger->customer_id = $customer_id;
        $addCustomerLedger->date = $fixed_date ? date('d-m-Y', strtotime($fixed_date)) : $date;
        $addCustomerLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addCustomerLedger->{$voucher_type['id']} = $trans_id;
        $addCustomerLedger->{$voucher_type['amt']} = $amount;
        $addCustomerLedger->amount = $amount;
        $addCustomerLedger->amount_type = $voucher_type['amt'];
        $addCustomerLedger->voucher_type = $voucher_type_id;
        $addCustomerLedger->running_balance = 0;
        $addCustomerLedger->save();
    }

    public function updateCustomerLedger($voucher_type_id, $customer_id, $previous_branch_id, $new_branch_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);

        $updateCustomerLedger = CustomerLedger::where('customer_id', $customer_id)
            ->where('branch_id', $previous_branch_id)
            ->where($voucher_type['id'], $trans_id)
            ->where('voucher_type', $voucher_type_id)
            ->first();

        if ($updateCustomerLedger) {

            //$updateCustomerLedger->customer_id = $customer_id;
            $previousTime = date('H:i:s', strtotime($updateCustomerLedger->report_date));
            $updateCustomerLedger->branch_id = $new_branch_id ? $new_branch_id : $previous_branch_id;
            $updateCustomerLedger->date = $fixed_date ? date('d-m-Y', strtotime($fixed_date)) : $date;
            $updateCustomerLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $updateCustomerLedger->{$voucher_type['amt']} = $amount;
            $updateCustomerLedger->amount = $amount;
            $updateCustomerLedger->running_balance = 0;
            $updateCustomerLedger->save();
        } else {

            $this->addCustomerLedger($voucher_type_id, $customer_id, $new_branch_id, $date, $trans_id, $amount, $fixed_date);
        }
    }
}

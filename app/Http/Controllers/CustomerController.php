<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Account;
use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Utils\SaleUtil;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Utils\Converter;
use App\Models\SaleReturn;
use App\Utils\AccountUtil;
use App\Models\SalePayment;
use App\Utils\CustomerUtil;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use App\Utils\CustomerPaymentUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerCreditLimit;
use App\Models\CustomerOpeningBalance;
use App\Models\CustomerPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\BranchWiseCustomerAmountUtil;

class CustomerController extends Controller
{
    public $customerUtil;
    public $accountUtil;
    public $converter;
    public $invoiceVoucherRefIdUtil;
    public $userActivityLogUtil;
    public $saleUtil;
    public $customerPaymentUtil;
    public $branchWiseCustomerAmountUtil;

    public function __construct(
        CustomerUtil $customerUtil,
        AccountUtil $accountUtil,
        Converter $converter,
        SaleUtil $saleUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        CustomerPaymentUtil $customerPaymentUtil,
        BranchWiseCustomerAmountUtil $branchWiseCustomerAmountUtil
    ) {
        $this->customerUtil = $customerUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->saleUtil = $saleUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->customerPaymentUtil = $customerPaymentUtil;
        $this->branchWiseCustomerAmountUtil = $branchWiseCustomerAmountUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->contact['customer_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->customerUtil->customerListTable($request);
        }
        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }
        $groups = DB::table('customer_groups')->get();
        return view('contacts.customers.index', compact('groups', 'branches'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->contact['customer_add'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'add_branch_id' => 'required',
        ]);

        $generalSettings = DB::table('general_settings')->first('prefix');

        $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];

        $creditLimit = $request->credit_limit ? $request->credit_limit : 0;

        $branch = DB::table('branches')->where('id', $request->add_branch_id)->select('id', 'name', 'branch_code')->first();

        // $branchUser = getBranchUser($request->add_branch_id);

        $addCustomer = Customer::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, "0", STR_PAD_LEFT),
            // 'admin_user_id' => $branchUser->id,
            'admin_user_id' => auth()->user()->id,
            'branch_id' => $request->add_branch_id,
            'name' => $request->name,
            'business_name' => $branch->name,
            // 'business_name' => $branch->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            // 'pay_term' => $request->pay_term,
            // 'pay_term_number' => $request->pay_term_number,
            'customer_group_id' => $request->customer_group_id,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
            // 'credit_limit' => $request->credit_limit,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        // $branchUser = getBranchUser($request->add_branch_id);

        $addCreditLimit = new CustomerCreditLimit();
        $addCreditLimit->customer_id = $addCustomer->id;
        $addCreditLimit->branch_id = $request->add_branch_id;
        // $addCreditLimit->created_by_id = $branchUser->id;
        $addCreditLimit->created_by_id = auth()->user()->id;
        $addCreditLimit->credit_limit = $creditLimit ? $creditLimit : 0;
        $addCreditLimit->pay_term = $request->pay_term;
        $addCreditLimit->pay_term_number = $request->pay_term_number;
        $addCreditLimit->save();

        // Add Customer Ledger
        $this->customerUtil->addCustomerLedger(
            voucher_type_id: 0,
            customer_id: $addCustomer->id,
            branch_id: $request->add_branch_id,
            date: date('Y-m-d'),
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0.00
        );

        // $branchUser = getBranchUser($request->add_branch_id);

        $addCustomerOpeningBalance = new CustomerOpeningBalance();
        $addCustomerOpeningBalance->customer_id = $addCustomer->id;
        $addCustomerOpeningBalance->branch_id = $request->add_branch_id;
        // $addCustomerOpeningBalance->created_by_id = $branchUser->id;
        $addCustomerOpeningBalance->created_by_id =  auth()->user()->id;
        $addCustomerOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
        $addCustomerOpeningBalance->save();

        $this->userActivityLogUtil->addLog(action: 1, subject_type: 1, data_obj: $addCustomer);

        return $addCustomer;
    }

    public function edit($customerId)
    {
        if (auth()->user()->permission->contact['customer_edit'] == '0') {

            return response()->json('Access Denied');
        }

        $customer = DB::table('customers')->where('id', $customerId)->first();

        $branch = Branch::where('id', $customer->branch_id)->get(['id', 'name', 'branch_code'])->first();

        $branchOpeningBalance = DB::table('customer_opening_balances')
            ->where('customer_id', $customer->id)->where('branch_id', $customer->branch_id)
            ->first();

        $customerCreditLimit = DB::table('customer_credit_limits')
            ->where('customer_id', $customer->id)->where('branch_id', $customer->branch_id)
            ->first();

        $groups = DB::table('customer_groups')->get();

        return view('contacts.customers.ajax_view.edit', compact('customer', 'groups', 'branchOpeningBalance', 'customerCreditLimit', 'branch'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->contact['customer_edit'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'update_branch_id' => 'required',
        ]);

        // $branchUser = getBranchUser($request->update_branch_id);

        $creditLimit = $request->credit_limit ? $request->credit_limit : 0;
        $updateCustomer = Customer::where('id', $request->id)->first();
        $previous_branch_id = $updateCustomer->branch_id;
        // $updateCustomer->admin_user_id = $branchUser->id;
        $updateCustomer->admin_user_id = auth()->user()->id;
        $updateCustomer->branch_id = $request->update_branch_id;
        $updateCustomer->name = $request->name;
        $updateCustomer->business_name = $request->business_name;
        $updateCustomer->email = $request->email;
        $updateCustomer->phone = $request->phone;
        $updateCustomer->alternative_phone = $request->alternative_phone;
        $updateCustomer->landline = $request->landline;
        $updateCustomer->date_of_birth = $request->date_of_birth;
        $updateCustomer->tax_number = $request->tax_number;
        $updateCustomer->pay_term = $request->pay_term;
        $updateCustomer->pay_term_number = $request->pay_term_number;
        $updateCustomer->customer_group_id = $request->customer_group_id;
        $updateCustomer->address = $request->address;
        $updateCustomer->city = $request->city;
        $updateCustomer->zip_code = $request->zip_code;
        $updateCustomer->country = $request->country;
        $updateCustomer->state = $request->state;
        $updateCustomer->shipping_address = $request->shipping_address;
        // $updateCustomer->credit_limit = $request->credit_limit;
        // $updateCustomer->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $updateCustomer->save();

        $customerCreditLimit = CustomerCreditLimit::where('customer_id', $updateCustomer->id)->where('branch_id', $previous_branch_id)->first();


        if ($customerCreditLimit) {

            $customerCreditLimit->credit_limit = $creditLimit ? $creditLimit : 0;
            $customerCreditLimit->pay_term = $request->pay_term;
            $customerCreditLimit->pay_term_number = $request->pay_term_number;
            $customerCreditLimit->save();
        } else {

            $addCreditLimit = new CustomerCreditLimit();
            $addCreditLimit->customer_id = $updateCustomer->id;
            $addCreditLimit->branch_id = $request->update_branch_id;
            $addCreditLimit->created_by_id = auth()->user()->id;
            $addCreditLimit->credit_limit = $creditLimit ? $creditLimit : 0;
            $addCreditLimit->pay_term = $request->pay_term;
            $addCreditLimit->pay_term_number = $request->pay_term_number;
            $addCreditLimit->save();
        }

        $userOpeningBalance = CustomerOpeningBalance::where('customer_id', $updateCustomer->id)->where('branch_id', $previous_branch_id)->first();

        if ($userOpeningBalance) {

            $userOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $userOpeningBalance->save();
        } else {

            $addCustomerOpeningBalance = new CustomerOpeningBalance();
            $addCustomerOpeningBalance->customer_id = $updateCustomer->id;
            $addCustomerOpeningBalance->branch_id = $request->update_branch_id;
            $addCustomerOpeningBalance->created_by_id = auth()->user()->id;
            $addCustomerOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $addCustomerOpeningBalance->save();
        }

        $calcOpeningBalance = DB::table('customer_opening_balances')
            ->where('customer_id', $updateCustomer->id)
            ->select(DB::raw('SUM(amount) as op_amount'))
            ->groupBy('customer_id')->get();

        $updateCustomer->opening_balance = $calcOpeningBalance->sum('op_amount');
        $updateCustomer->save();

        $customer = DB::table('customers')
            ->where('id', $updateCustomer->id)
            ->select('name', 'phone', 'contact_id', 'total_sale_due')
            ->first();

        $this->customerUtil->updateCustomerLedger(
            voucher_type_id: 0,
            customer_id: $updateCustomer->id,
            previous_branch_id: $previous_branch_id,
            new_branch_id: $request->update_branch_id,
            date: $updateCustomer->created_at,
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0.00,
            fixed_date: $updateCustomer->created_at,
        );

        $customer = DB::table('customers')->where('id', $updateCustomer->id)
            ->select('name', 'phone', 'contact_id', 'total_sale_due')
            ->first();

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $customer);


            return response()->json(__('Customer updated successfully'));


    }

    public function delete(Request $request, $customerId)
    {
        if (auth()->user()->permission->contact['customer_delete'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteCustomer = Customer::find($customerId);

        if (!is_null($deleteCustomer)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 1, data_obj: $deleteCustomer);

            $deleteCustomer->delete();
        }

            return response()->json(__('Customer deleted successfully'));


    }

    // Change status method
    public function changeStatus($customerId)
    {
        $statusChange = Customer::where('id', $customerId)->first();

        if ($statusChange->status == 1) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $statusChange);
            $statusChange->status = 0;
            $statusChange->save();

          return response()->json(__('Customer deactivated successfully'));


        } else {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $statusChange);
            $statusChange->status = 1;
            $statusChange->save();

                return response()->json(__('Customer activated successfully'));


        }
    }

    // Customer view method
    public function view(Request $request, $customerId)
    {
        $customerId = $customerId;
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $sales = '';
            $query = DB::table('sales')
                ->where('sales.customer_id', $customerId)
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('sales.branch_id', NULL);
                } else {

                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('sales.report_date', $date_range); // Final
            }

            $query->select(
                'sales.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $sales = $query->where('sales.status', 1)->orderBy('sales.report_date', 'desc');
            } else {

                if (auth()->user()->can('view_own_sale')) {

                    $query->where('sales.admin_id', auth()->user()->id);
                }

                $sales = $query->where('sales.branch_id', auth()->user()->branch_id)->where('sales.status', 1)->orderBy('sales.report_date', 'desc');
            }

            return DataTables::of($sales)

                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i
                                        class="far fa-eye text-primary"></i> View</a>';

                        $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("Packing Slip").' </a>';

                        if (auth()->user()->permission->sale['shipment_access'] == '1') {

                            $html .= '<a class="dropdown-item" id="edit_shipment"
                                href="' . route('sales.shipment.edit', [$row->id]) . '"><i
                                class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                        }

                        if (auth()->user()->branch_id == $row->branch_id) {

                            if ($row->due > 0) {

                                if (auth()->user()->permission->sale['sale_payment'] == '1') {

                                    $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Add Payment").'</a>';
                                }
                            }

                            if (auth()->user()->permission->sale['sale_payment'] == '1') {

                                $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                                data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                                class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                            }

                            if ($row->created_by == 1) {

                                $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                            } else {

                                $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                            }

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i
                            class="far fa-trash-alt text-primary"></i> Delete</a>';
                        }

                        if ($row->sale_return_due > 0) {

                            if (auth()->user()->permission->sale['sale_payment'] == '1') {

                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '" ><i class="far fa-money-bill-alt text-primary"></i> '.__("Pay Return Amount").'</a>';
                            }
                        }

                        // $html .= '<a class="dropdown-item" id="items_notification" href=""><i
                        //                 class="fas fa-envelope text-primary"></i> New Sale Notification</a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })

                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })

                ->editColumn('invoice_id', function ($row) {

                    $html = '';
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })

                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', fn ($row) => '<span class="text-success total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')

                ->editColumn('paid', fn ($row) => '<span class="text-success paid" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

                ->editColumn('due', fn ($row) =>  '<span class="text-danger due" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

                ->editColumn('sale_return_amount', fn ($row) => '<span class="text-danger sale_return_amount" data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')

                ->editColumn('sale_return_due', fn ($row) => '<span class="text-danger sale_return_due" data-value="' . $row->sale_return_due . '">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')

                ->editColumn('paid_status', function ($row) {

                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    if ($row->due <= 0) {

                        return '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {

                        return '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {

                        return '<span class="text-danger"><b>Due</b></span>';
                    }
                })
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
                ->make(true);
        }

        $customer = DB::table('customers')->where('id', $customerId)->first();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('contacts.customers.view', compact('customerId', 'customer', 'branches'));
    }

    // Customer ledger list
    public function ledgerList(Request $request, $customerId)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->first();

            $customerUtil = $this->customerUtil;

            $customerLedgers = '';

            $query = DB::table('customer_ledgers')->where('customer_ledgers.customer_id', $customerId)
                ->leftJoin('branches', 'customer_ledgers.branch_id', 'branches.id')
                ->leftJoin('sales', 'customer_ledgers.sale_id', 'sales.id')
                ->leftJoin('sale_returns', 'customer_ledgers.sale_return_id', 'sale_returns.id')
                ->leftJoin('sale_payments', 'customer_ledgers.sale_payment_id', 'sale_payments.id')
                ->leftJoin('customer_payments', 'customer_ledgers.customer_payment_id', 'customer_payments.id')
                ->leftJoin('sales as ags_sale', 'sale_payments.sale_id', 'ags_sale.id')
                ->select(
                    'customer_ledgers.report_date',
                    'customer_ledgers.voucher_type',
                    'customer_ledgers.debit',
                    'customer_ledgers.credit',
                    'customer_ledgers.running_balance',
                    'branches.name as b_name',
                    'sales.invoice_id as sale_inv_id',
                    'sales.status as sale_status',
                    'sales.sale_note as sale_par',
                    'sale_returns.invoice_id as return_inv_id',
                    'sale_returns.date as sale_return_par',
                    'sale_payments.invoice_id as sale_payment_voucher',
                    'sale_payments.note as sale_payment_par',
                    'customer_payments.voucher_no as customer_payment_voucher',
                    'customer_payments.less_amount',
                    'customer_payments.note as customer_payment_par',
                    'ags_sale.invoice_id as ags_sale',
                )->orderBy('customer_ledgers.report_date', 'asc');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('customer_ledgers.branch_id', NULL);
                } else {

                    $query->where('customer_ledgers.branch_id', $request->branch_id);
                }
            }

            if ($request->voucher_type) {

                $query->where('customer_ledgers.voucher_type', $request->voucher_type); // Final
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('customer_ledgers.report_date', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $customerLedgers = $query->orderBy('customer_ledgers.report_date', 'asc');
            } else {

                $customerLedgers = $query->where('customer_ledgers.branch_id', auth()->user()->branch_id)
                    ->orderBy('customer_ledgers.report_date', 'desc');
            }

            $customerLedgers = $customerLedgers->get();
            $tempRunning = 0;
            foreach ($customerLedgers as $customerLedger) {

                $customerLedger->running_balance =  $tempRunning + ($customerLedger->debit - ($customerLedger->credit + $customerLedger->less_amount));
                $tempRunning = $customerLedger->running_balance;
            }

            return DataTables::of($customerLedgers)
                ->editColumn('date', function ($row) use ($settings) {

                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);
                    return date($__date_format, strtotime($row->report_date));
                })

                ->editColumn('particulars', function ($row) use ($customerUtil) {

                    $type = $customerUtil->voucherType($row->voucher_type);
                    $__agp = $row->ags_sale ? '/' . 'AGS:<b>' . $row->ags_sale . '</b>' : '';
                    $__less = $row->less_amount > 0 ? '/' . 'Less:(<b class="text-danger">' . $row->less_amount . '</b>)' : '';
                    return '<b>' . $type['name'] . ($row->sale_status == 3 ? '-Order' : '') . '</b>' . $__agp . $__less . ($row->{$type['par']} ? '/' . $row->{$type['par']} : '');
                })

                ->editColumn('b_name', function ($row) use ($settings) {

                    if ($row->b_name) {

                        return $row->b_name;
                    } else {
                        return json_decode($settings->business, true)['shop_name'];
                    }
                })

                ->editColumn('voucher_no', function ($row) use ($customerUtil) {

                    $type = $customerUtil->voucherType($row->voucher_type);
                    return $row->{$type['voucher_no']};
                })

                ->editColumn('debit', fn ($row) => '<span class="debit" data-value="' . $row->debit . '">' . $this->converter->format_in_bdt($row->debit) . '</span>')

                ->editColumn('credit', fn ($row) => '<span class="credit" data-value="' . $row->credit . '">' . $this->converter->format_in_bdt($row->credit) . '</span>')

                ->editColumn('running_balance', function ($row) {

                    return '<span class="running_balance">' . $this->converter->format_in_bdt($row->running_balance) . '</span>';
                })

                ->rawColumns(['date', 'particulars', 'b_name', 'voucher_no', 'debit', 'credit', 'running_balance'])
                ->make(true);
        }

        $customer = DB::table('customers')->where('id', $customerId)->select('id', 'contact_id', 'name')->first();
        return view('contacts.customers.ajax_view.ledger_list', compact('ledgers', 'customer'));
    }

    // Customer ledger list
    public function ledgerPrint(Request $request, $customerId)
    {
        $customerUtil = $this->customerUtil;
        $branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
        $ledgers = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('customer_ledgers')->where('customer_ledgers.customer_id', $customerId)
            ->leftJoin('sales', 'customer_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'customer_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('sale_payments', 'customer_ledgers.sale_payment_id', 'sale_payments.id')
            ->leftJoin('customer_payments', 'customer_ledgers.customer_payment_id', 'customer_payments.id')
            ->leftJoin('sales as ags_sale', 'sale_payments.sale_id', 'ags_sale.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('customer_ledgers.branch_id', NULL);
            } else {

                $query->where('customer_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->voucher_type) {

            $query->where('customer_ledgers.voucher_type', $request->voucher_type); // Final
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('customer_ledgers.report_date', $date_range); // Final

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        $query->select(
            'customer_ledgers.report_date',
            'customer_ledgers.voucher_type',
            'customer_ledgers.debit',
            'customer_ledgers.credit',
            'customer_ledgers.running_balance',
            'sales.invoice_id as sale_inv_id',
            'sales.sale_note as sale_par',
            'sales.status as sale_status',
            'sale_returns.invoice_id as return_inv_id',
            'sale_returns.date as sale_return_par',
            'sale_payments.invoice_id as sale_payment_voucher',
            'sale_payments.note as sale_payment_par',
            'customer_payments.voucher_no as customer_payment_voucher',
            'customer_payments.less_amount',
            'customer_payments.note as customer_payment_par',
            'ags_sale.invoice_id as ags_sale',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $ledgers = $query->orderBy('customer_ledgers.report_date', 'asc')->get();
        } else {

            $ledgers = $query->where('customer_ledgers.branch_id', auth()->user()->branch_id)
                ->orderBy('customer_ledgers.report_date', 'asc')->get();
        }

        $customer = DB::table('customers')->where('id', $customerId)
            ->select('id', 'contact_id', 'name', 'phone', 'address',)->first();

        return view('contacts.customers.ajax_view.print_ledger', compact('branch_id', 'ledgers', 'customer', 'customerUtil', 'fromDate', 'toDate'));
    }

    // Customer payment view
    public function payment($customerId)
    {
        $branch_id = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
        $customer = DB::table('customers')->where('id', $customerId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get([
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance'
            ]);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $amounts = $this->branchWiseCustomerAmountUtil->branchWiseCustomerAmount($customerId, $branch_id);

        $branchWiseCustomerInvoiceAndOrders = $this->branchWiseCustomerAmountUtil->branchWiseCustomerInvoiceAndOrders($customerId, $branch_id);

        return view('contacts.customers.ajax_view.payment_modal', compact('customer', 'accounts', 'methods', 'amounts', 'branchWiseCustomerInvoiceAndOrders'));
    }

    public function paymentAdd(Request $request, $customerId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly
            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

            // Add Customer Payment Record
            $customerPayment = new CustomerPayment();
            $customerPayment->voucher_no = 'CPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customer_payments'), 5, "0", STR_PAD_LEFT);
            $customerPayment->reference = $request->reference;
            $customerPayment->branch_id = auth()->user()->branch_id;
            $customerPayment->customer_id = $customerId;
            $customerPayment->account_id = $request->account_id;
            $customerPayment->paid_amount = $request->paying_amount;
            $customerPayment->less_amount = $request->less_amount ? $request->less_amount : 0;
            $customerPayment->payment_method_id = $request->payment_method_id;
            $customerPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $customerPayment->date = $request->date;
            $customerPayment->time = date('h:i:s a');
            $customerPayment->month = date('F');
            $customerPayment->year = date('Y');

            if ($request->hasFile('attachment')) {

                $PaymentAttachment = $request->file('attachment');
                $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
                $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
                $customerPayment->attachment = $paymentAttachmentName;
            }

            $customerPayment->note = $request->note;
            $customerPayment->save();

            // Add supplier Ledger
            $this->customerUtil->addCustomerLedger(
                voucher_type_id: 5,
                customer_id: $customerId,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $customerPayment->id,
                amount: $request->paying_amount
            );

            // Add Bank/Cash-in-hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 18,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $customerPayment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            if (isset($request->sale_ids)) {

                $this->customerPaymentUtil->specificInvoiceOrOrderByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix);
            } else {

                $this->customerPaymentUtil->randomInvoiceOrSalesOrderPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix);
            }

            $receive = DB::table('customer_payments')
                ->where('customer_payments.id', $customerPayment->id)
                ->leftJoin('customers', 'customer_payments.customer_id', 'customers.id')
                ->leftJoin('payment_methods', 'customer_payments.payment_method_id', 'payment_methods.id')
                ->select(
                    'customer_payments.voucher_no',
                    'customer_payments.date',
                    'customer_payments.paid_amount',
                    'customers.name as customer',
                    'customers.phone',
                    'payment_methods.name as method',
                )->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 27, data_obj: $receive);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }


            return response()->json(__('Payment added successfully.'));


    }

    public function returnPayment($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get([
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance'
            ]);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $branch_id = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;

        $returnDue = $this->branchWiseCustomerAmountUtil->branchWiseCustomerAmount($customerId, $branch_id)['total_sale_return_due'];

        return view('contacts.customers.ajax_view.return_payment_modal', compact('customer', 'accounts', 'methods', 'returnDue'));
    }

    public function returnPaymentAdd(Request $request, $customerId)
    {
        // Add Customer Payment Record
        $customerPayment = new CustomerPayment();
        $customerPayment->voucher_no = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customer_payments'), 5, "0", STR_PAD_LEFT);
        $customerPayment->branch_id = auth()->user()->branch_id;
        $customerPayment->customer_id = $customerId;
        $customerPayment->account_id = $request->account_id;
        $customerPayment->paid_amount = $request->paying_amount;
        $customerPayment->type = 2;
        $customerPayment->payment_method_id = $request->payment_method_id;
        $customerPayment->date = $request->date;
        $customerPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $customerPayment->time = date('h:i:s a');
        $customerPayment->month = date('F');
        $customerPayment->year = date('Y');

        if ($request->hasFile('attachment')) {

            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $customerPayment->attachment = $paymentAttachmentName;
        }

        $customerPayment->note = $request->note;
        $customerPayment->save();

        // Add supplier Ledger
        $this->customerUtil->addCustomerLedger(
            voucher_type_id: 6,
            customer_id: $customerId,
            branch_id: auth()->user()->branch_id,
            date: $request->date,
            trans_id: $customerPayment->id,
            amount: $request->paying_amount
        );

        // Add Bank/Cash-in-hand A/C Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 20,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $customerPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        // $returnSales = Sale::with(['sale_return'])->where('sale_return_due', '>', 0)->get();
        $saleReturns = SaleReturn::with(['sale'])->where('customer_id', $customerId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('total_return_due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($saleReturns) > 0) {

            $index = 0;
            foreach ($saleReturns as $saleReturn) {

                if ($saleReturn->total_return_due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleUtil->saleReturnPaymentGetId(
                            request: $request,
                            sale: $saleReturn->sale,
                            customer_payment_id: $customerPayment->id,
                            sale_return_id: $saleReturn->id
                        );

                        // Add customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $saleReturn->sale_id;
                        $addCustomerPaymentInvoice->sale_return_id = $saleReturn->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->paying_amount;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        $request->paying_amount -= $request->paying_amount;

                        if ($saleReturn->sale) {

                            $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
                        }

                        // Update sale return
                        $this->saleUtil->adjustSaleReturnAmounts($saleReturn);
                    }
                } elseif ($saleReturn->total_return_due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleUtil->saleReturnPaymentGetId(
                            request: $request,
                            sale: $saleReturn->sale,
                            customer_payment_id: $customerPayment->id,
                            sale_return_id: $saleReturn->id
                        );

                        // Add Customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $saleReturn->sale_id;
                        $addCustomerPaymentInvoice->sale_return_id = $saleReturn->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->paying_amount;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        $request->paying_amount -= $request->paying_amount;

                        if ($saleReturn->sale) {

                            $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
                        }

                        // Update sale return
                        $this->saleUtil->adjustSaleReturnAmounts($saleReturn);
                    }
                } elseif ($saleReturn->total_return_due < $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleUtil->saleReturnPaymentGetId(
                            request: $request,
                            sale: $saleReturn->sale,
                            customer_payment_id: $customerPayment->id,
                            sale_return_id: $saleReturn->id
                        );

                        // Add Customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $saleReturn->sale_id;
                        $addCustomerPaymentInvoice->sale_return_id = $saleReturn->id;
                        $addCustomerPaymentInvoice->paid_amount = $saleReturn->total_return_due;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        $request->paying_amount -= $saleReturn->total_return_due;

                        if ($saleReturn->sale) {

                            $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
                        }

                        // Update sale return
                        $this->saleUtil->adjustSaleReturnAmounts($saleReturn);
                    }
                }
                $index++;
            }
        }

        $this->customerUtil->adjustCustomerAmountForSalePaymentDue($customerId);

        return response()->json(__('Return amount paid successfully.'));

    }

    public function viewPayment($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();

        $customer_payments = DB::table('customer_payments')
            ->leftJoin('accounts', 'customer_payments.account_id', 'accounts.id')
            ->leftJoin('payment_methods', 'customer_payments.payment_method_id', 'payment_methods.id')
            ->where('customer_payments.customer_id', $customerId)
            ->select(
                'customer_payments.*',
                'accounts.name as ac_name',
                'accounts.account_number as ac_no',
                'payment_methods.name as payment_method'
            )->orderBy('customer_payments.report_date', 'desc')->get();

        return view('contacts.customers.ajax_view.view_payment_list', compact('customer', 'customer_payments'));
    }

    // Customer Payment Details
    public function paymentDetails($paymentId)
    {
        $customerPayment = CustomerPayment::with(
            'branch',
            'customer',
            'account',
            'customer_payment_invoices',
            'customer_payment_invoices.sale:id,invoice_id,date',
            'customer_payment_invoices.sale_return',
            'paymentMethod:id,name'
        )->where('id', $paymentId)->first();

        return view('contacts.customers.ajax_view.payment_details', compact('customerPayment'));
    }

    // Customer Payment Delete
    public function paymentDelete(Request $request, $paymentId)
    {
        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly
            $deleteCustomerPayment = CustomerPayment::with(['customer_payment_invoices', 'customer_payment_invoices.sale', 'customer_payment_invoices.sale_return'])->where('id', $paymentId)->first();

            if ($deleteCustomerPayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $deleteCustomerPayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $deleteCustomerPayment->attachment));
                }
            }

            $storedAccountId = $deleteCustomerPayment->account_id;
            $storedCustomerPaymentType = $deleteCustomerPayment->type;
            $storedCustomerId = $deleteCustomerPayment->customer_id;
            $storedCustomerPaymentInvoices = $deleteCustomerPayment->customer_payment_invoices;

            $customerPayment = DB::table('customer_payments')
                ->where('customer_payments.id', $deleteCustomerPayment->id)
                ->leftJoin('customers', 'customer_payments.customer_id', 'customers.id')
                ->leftJoin('payment_methods', 'customer_payments.payment_method_id', 'payment_methods.id')
                ->select(
                    'customer_payments.voucher_no',
                    'customer_payments.date',
                    'customer_payments.paid_amount',
                    'customers.name as customer',
                    'customers.phone',
                    'payment_methods.name as method',
                )->first();

            if ($storedCustomerPaymentType == 1) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 27, data_obj: $customerPayment);
            }

            $deleteCustomerPayment->delete();

            // Update Customer payment invoices
            if (count($storedCustomerPaymentInvoices) > 0) {

                if ($storedCustomerPaymentType == 1) {

                    foreach ($deleteCustomerPayment->customer_payment_invoices as $customer_payment_invoice) {

                        $sale = Sale::where('id', $customer_payment_invoice->sale_id)->first();

                        if ($sale) {

                            $this->saleUtil->adjustSaleInvoiceAmounts($sale);
                        }
                    }
                } else {

                    foreach ($deleteCustomerPayment->customer_payment_invoices as $customer_payment_invoice) {

                        $saleReturn = SaleReturn::with(['sale'])->where('id', $customer_payment_invoice->sale_return_id)->first();

                        if ($saleReturn->sale) {

                            $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
                        }

                        $this->saleUtil->adjustSaleReturnAmounts($saleReturn);
                    }
                }
            }

            if ($storedAccountId) {

                $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
            }

            //Update customer info
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payment deleted successfully.'));

    }

    public function allPaymentList(Request $request, $customerId)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $payments = '';
            $paymentsQuery = DB::table('customer_ledgers')
                ->where('customer_ledgers.customer_id', $customerId)
                ->whereIn('customer_ledgers.voucher_type', [3, 4, 5, 6])
                ->leftJoin('customer_payments', 'customer_ledgers.customer_payment_id', 'customer_payments.id')
                ->leftJoin('payment_methods as cp_pay_method', 'customer_payments.payment_method_id', 'cp_pay_method.id')
                ->leftJoin('accounts as cp_account', 'customer_payments.account_id', 'cp_account.id')
                ->leftJoin('sale_payments', 'customer_ledgers.sale_payment_id', 'sale_payments.id')
                ->leftJoin('payment_methods as sp_pay_method', 'sale_payments.payment_method_id', 'sp_pay_method.id')
                ->leftJoin('accounts as sp_account', 'sale_payments.account_id', 'sp_account.id')
                ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
                ->leftJoin('sale_returns', 'sale_payments.sale_return_id', 'sale_returns.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $paymentsQuery->where('customer_ledgers.branch_id', NULL);
                } else {

                    $paymentsQuery->where('customer_ledgers.branch_id', $request->branch_id);
                }
            }

            if ($request->p_from_date) {

                $from_date = date('Y-m-d', strtotime($request->p_from_date));
                $to_date = $request->p_to_date ? date('Y-m-d', strtotime($request->p_to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $paymentsQuery->whereBetween('customer_ledgers.report_date', $date_range); // Final
            }

            $payments = $paymentsQuery->select(
                'customer_ledgers.date',
                'customer_ledgers.report_date',
                'customer_ledgers.amount',
                'customer_ledgers.customer_payment_id',
                'customer_ledgers.sale_payment_id',
                'customer_ledgers.voucher_type',
                'customer_payments.voucher_no as customer_payment_voucher',
                'customer_payments.pay_mode as cp_pay_mode',
                'customer_payments.reference',
                'customer_payments.less_amount',
                'cp_pay_method.name as cp_payment_method',
                'cp_account.name as cp_account',
                'cp_account.account_number as cp_account_number',
                'sale_payments.invoice_id as sale_payment_voucher',
                'sale_payments.pay_mode as sp_pay_mode',
                'sp_pay_method.name as sp_payment_method',
                'sp_account.name as sp_account',
                'sp_account.account_number as sp_account_number',
                'sales.invoice_id as sale_inv',
                'sales.invoice_id as return_inv',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $payments = $paymentsQuery->orderBy('customer_ledgers.report_date', 'desc');
            } else {

                $payments = $paymentsQuery->where('customer_ledgers.branch_id', auth()->user()->branch_id)->orderBy('customer_ledgers.report_date', 'desc');
            }

            return DataTables::of($payments)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';

                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if ($row->customer_payment_id) {

                        $html .= '<a href="' . route('customers.view.details', $row->customer_payment_id) . '" id="payment_details" class="dropdown-item"><i class="fas fa-eye text-primary"></i> '.__("Details").'</a>';

                        $html .= '<a href="' . route('customers.payment.delete', $row->customer_payment_id) . '" id="delete_payment" class="dropdown-item"><i class="far fa-trash-alt text-danger"></i> '.__("Delete").'</a>';
                    } else {

                        $html .= '<a href="' . route('sales.payment.details', $row->sale_payment_id) . '" id="payment_details" class="dropdown-item"><i class="fas fa-eye text-primary"></i> '.__("Details").'</a>';

                        $html .= '<a href="' . route('sales.payment.delete', $row->sale_payment_id) . '" id="delete_payment" class="dropdown-item"><i class="far fa-trash-alt text-danger"></i> '.__("Delete").'</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('voucher_no', function ($row) {

                    return $row->customer_payment_voucher . $row->sale_payment_voucher;
                })
                ->editColumn('against_invoice', function ($row) {

                    if ($row->sale_inv || $row->return_inv) {

                        if ($row->sale_inv) {

                            return 'Sale : ' . $row->sale_inv;
                        } else {

                            return 'Sale Return : ' . $row->return_inv;
                        }
                    } else {

                        if ($row->customer_payment_id) {

                            return '<a href="' . route('customers.view.details', $row->customer_payment_id) . '" class="btn btn-sm text-info" id="payment_details"> Details</a>';
                        } else {

                            return '<a href="' . route('sales.payment.details', $row->sale_payment_id) . '" class="btn btn-sm text-info" id="payment_details"> Details</a>';
                        }
                    }
                })
                ->editColumn('type', function ($row) {

                    if ($row->voucher_type == 3 || $row->voucher_type == 5) {

                        return 'Received Payment';
                    } else {

                        return 'Return Payment';
                    }
                })
                ->editColumn('method', function ($row) {

                    return $row->cp_pay_mode . $row->cp_payment_method . $row->sp_pay_mode . $row->sp_payment_method;
                })
                ->editColumn('account', function ($row) {

                    if ($row->cp_account) {

                        return $row->cp_account . '(A/C:' . $row->cp_account_number . ')';
                    } else {

                        return $row->sp_account . '(A/C:' . $row->sp_account_number . ')';
                    }
                })

                ->editColumn('less_amount', fn ($row) => '<span class="less_amount" data-value="' . $row->less_amount . '">' . $this->converter->format_in_bdt($row->less_amount) . '</span>')

                ->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')

                ->rawColumns(['date', 'against_invoice', 'type', 'method', 'account', 'less_amount', 'amount', 'action'])
                ->make(true);
        }
    }

    public function allPaymentPrint(Request $request, $customerId)
    {
        $branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
        $payments = '';
        $fromDate  = '';
        $toDate = '';
        $customer = DB::table('customers')->where('id', $customerId)->first();

        $paymentsQuery = DB::table('customer_ledgers')
            ->where('customer_ledgers.customer_id', $customerId)
            ->whereIn('customer_ledgers.voucher_type', [3, 4, 5, 6])
            ->leftJoin('customer_payments', 'customer_ledgers.customer_payment_id', 'customer_payments.id')
            ->leftJoin('payment_methods as cp_pay_method', 'customer_payments.payment_method_id', 'cp_pay_method.id')
            ->leftJoin('accounts as cp_account', 'customer_payments.account_id', 'cp_account.id')
            ->leftJoin('sale_payments', 'customer_ledgers.sale_payment_id', 'sale_payments.id')
            ->leftJoin('payment_methods as sp_pay_method', 'sale_payments.payment_method_id', 'sp_pay_method.id')
            ->leftJoin('accounts as sp_account', 'sale_payments.account_id', 'sp_account.id')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'sale_payments.sale_return_id', 'sale_returns.id')
            // ->leftJoin('admin_and_users', 'customer_ledgers.user_id', 'admin_and_users.id')
        ;

        if ($request->type) {

            if ($request->type == 1) {

                $paymentsQuery->whereIn('customer_ledgers.voucher_type', [3, 5]);
            } else {

                $paymentsQuery->whereIn('customer_ledgers.voucher_type', [4, 6]);
            }
        }

        if ($request->p_from_date) {

            $from_date = date('Y-m-d', strtotime($request->p_from_date));
            $to_date = $request->p_to_date ? date('Y-m-d', strtotime($request->p_to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $paymentsQuery->whereBetween('customer_ledgers.report_date', $date_range); // Final

            $fromDate  = $request->p_from_date;
            $toDate = $request->p_to_date ? $request->p_to_date : $request->p_from_date;
        }

        $payments = $paymentsQuery->select(
            'customer_ledgers.date',
            'customer_ledgers.report_date',
            'customer_ledgers.amount',
            'customer_ledgers.customer_payment_id',
            'customer_ledgers.sale_payment_id',
            'customer_ledgers.voucher_type',
            'customer_payments.voucher_no as customer_payment_voucher',
            'customer_payments.reference',
            'customer_payments.less_amount',
            'customer_payments.pay_mode as cp_pay_mode',
            'cp_pay_method.name as cp_payment_method',
            'cp_account.name as cp_account',
            'cp_account.account_number as cp_account_number',
            'sale_payments.invoice_id as sale_payment_voucher',
            'sale_payments.pay_mode as sp_pay_mode',
            'sp_pay_method.name as sp_payment_method',
            'sp_account.name as sp_account',
            'sp_account.account_number as sp_account_number',
            'sales.invoice_id as sale_inv',
            'sales.invoice_id as return_inv',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $payments = $paymentsQuery->orderBy('customer_ledgers.report_date', 'desc')->get();
        } else {

            $payments = $paymentsQuery->where('customer_ledgers.branch_id', auth()->user()->branch_id)->orderBy('customer_ledgers.report_date', 'desc')->get();
        }

        return view('contacts.customers.ajax_view.print_payments', compact('payments', 'fromDate', 'toDate', 'customer', 'branch_id'));
    }

    public function customerAmountsBranchWise(Request $request, $customerId)
    {
        return $this->branchWiseCustomerAmountUtil->branchWiseCustomerAmount($customerId, $request->branch_id, $request->from_date, $request->to_date);
    }

    public function addOpeningBalance(Request $request)
    {
        $branch_id = $request->branch_id == 'NULL' ? NULL : $request->branch_id;
        $updateCustomer = Customer::where('id', $request->customer_id)->first();

        $branchOpeningBalance = CustomerOpeningBalance::where('customer_id', $request->customer_id)->where('branch_id', $branch_id)->first();

        if ($branchOpeningBalance) {

            $branchOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $branchOpeningBalance->is_show_again = isset($request->never_show_again) ? 0 : 1;
            $branchOpeningBalance->save();
        } else {

            $addCustomerOpeningBalance = new CustomerOpeningBalance();
            $addCustomerOpeningBalance->customer_id = $updateCustomer->id;
            $addCustomerOpeningBalance->branch_id = $branch_id;
            $addCustomerOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $addCustomerOpeningBalance->is_show_again = isset($request->never_show_again) ? 0 : 1;
            $addCustomerOpeningBalance->created_by_id = auth()->user()->id;
            $addCustomerOpeningBalance->save();
        }

        $calcOpeningBalance = DB::table('customer_opening_balances')
            ->where('customer_id', $request->customer_id)
            ->select(DB::raw('SUM(amount) as op_amount'))
            ->groupBy('customer_id')->get();

        $updateCustomer->opening_balance = $calcOpeningBalance->sum('op_amount');
        $updateCustomer->save();

        $this->customerUtil->updateCustomerLedger(
            voucher_type_id: 0,
            customer_id: $updateCustomer->id,
            previous_branch_id: $branch_id,
            new_branch_id: $branch_id,
            date: $updateCustomer->created_at,
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0.00,
            fixed_date: $updateCustomer->created_at,
        );

        return 'success';
    }
}

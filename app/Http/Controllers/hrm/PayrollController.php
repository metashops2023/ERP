<?php

namespace App\Http\Controllers\hrm;

use DateTime;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Hrm\Payroll;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Decimal;
use App\Models\Hrm\PayrollPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Hrm\PayrollAllowance;
use App\Models\Hrm\PayrollDeduction;
use App\Utils\AccountUtil;
use App\Utils\Hrm\PayrollUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    protected $invoiceVoucherRefIdUtil;
    protected $accountUtil;
    protected $payrollUtil;

    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        AccountUtil $accountUtil,
        PayrollUtil $payrollUtil
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->accountUtil = $accountUtil;
        $this->payrollUtil = $payrollUtil;
        $this->middleware('auth:admin_and_user');
    }

    //Index view of payroll
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payrolls = '';
            $query = DB::table('hrm_payrolls')
                ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
                ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
                ->leftJoin('hrm_designations', 'admin_and_users.designation_id', 'hrm_designations.id')
                ->leftJoin('admin_and_users as created_by', 'hrm_payrolls.admin_id', 'created_by.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('admin_and_users.branch_id', NULL);
                } else {
                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('hrm_payrolls.user_id', $request->user_id);
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];

                $query->whereBetween('hrm_payrolls.report_date_ts', $date_range); // Final
            }

            $query->select(
                'hrm_payrolls.*',
                'admin_and_users.prefix as emp_prefix',
                'admin_and_users.name as emp_name',
                'admin_and_users.last_name as emp_last_name',
                'admin_and_users.branch_id',
                'hrm_department.department_name',
                'hrm_designations.designation_name',
                'created_by.prefix as user_prefix',
                'created_by.name as user_name',
                'created_by.last_name as user_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $payrolls = $query;
            } else {
                $payrolls = $query->where('admin_and_users.branch_id', auth()->user()->branch_id);
            }

            return DataTables::of($payrolls)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="btn-group" role="group">';


                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu">';
                        $html .= '<a href="' . route('hrm.payrolls.show', [$row->id]) . '" class="dropdown-item" id="view_payroll"><i class="far fa-eye text-primary"></i> View</a>';

                        $html .= '<a href="' . route('hrm.payrolls.payment.view', [$row->id]) . '" class="dropdown-item" id="view_payment"><i class="far fa-money-bill-alt text-primary"></i>   '.__("View Payment").'</a>';

                        if (auth()->user()->branch_id == $row->branch_id) {
                            if ($row->due > 0) {
                                $html .= '<a href="' . route('hrm.payrolls.payment', [$row->id]) . '" class="dropdown-item" id="add_payment"><i class="far fa-money-bill-alt text-primary"></i> '.__("Pay Salary").'</a>';
                            }

                            $html .= '<a href="' . route('hrm.payrolls.edit', [$row->id]) . '" class="dropdown-item" id="edit"><i class="far fa-edit text-primary"></i>   '.__("Edit").'Edit</a>';
                            $html .= '<a href="' . route('hrm.payrolls.delete', [$row->id]) . '" class="dropdown-item" id="delete"><i class="far fa-trash-alt text-primary"></i>  '.__("Delete").' </a>';


                    }


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('employee', function ($row) {
                    return $row->emp_prefix . ' ' . $row->emp_name . ' ' . $row->emp_last_name;
                })
                ->editColumn('month_year', function ($row) {
                    return $row->month . '/' . $row->year;
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    if ($row->due <= 0) {
                        $html = '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $row->gross_amount) {
                        $html = '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($row->gross_amount == $row->due) {
                        $html = '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
                })
                ->rawColumns(['action', 'employee', 'month_year', 'payment_status', 'created_by'])
                ->make(true);
        }

        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $employee = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('hrm.payroll.index', compact('employee', 'departments', 'branches'));
    }

    // Create payroll
    public function create(Request $request)
    {
        // return  $result = (float)$float + $hour;
        // $number = str_replace(['+', '-'], '', filter_var($a, FILTER_SANITIZE_NUMBER_INT));

        $month_year = explode('-', $request->month_year);
        $year = $month_year[0];
        $dateTime = DateTime::createFromFormat('m', $month_year[1]);
        $month = $dateTime->format("F");

        // return $employee = AdminAndUser::where('id', $request->employee_id)->first();
        $payroll = DB::table('hrm_payrolls')->where('user_id', $request->user_id)->where('month', $month)->where('year', $year)->first();
        if ($payroll) {
            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        $employee = DB::table('admin_and_users')->where('id', $request->user_id)->first();
        $attendances = DB::table('hrm_attendances')->where('user_id', $request->employee_id)
            ->where('month', $month)->where('is_completed', 1)->get();

        $totalHours = 0;
        foreach ($attendances as $attendance) {
            $startTime = Carbon::parse($attendance->clock_in_ts);
            $endTime = Carbon::parse($attendance->clock_out_ts);
            $totalSeconds = $startTime->diffInSeconds($endTime);
            $munites = $totalSeconds / 60;
            $hours = $munites / 60;
            $totalHours += $hours;
            //gmdate('H:i:s', $totalHours);
        }

        $allowances = DB::table('hrm_allowance')->where('type', 'Allowance')->get();
        $deductions = DB::table('hrm_allowance')->where('type', 'Deduction')->get();

        return view('hrm.payroll.create', compact('employee', 'month', 'year', 'totalHours', 'allowances', 'deductions'));
    }

    // Store payroll
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ]);


        $addPayroll = new Payroll();
        $addPayroll->reference_no = 'EP' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('hrm_payrolls'), 4, "0", STR_PAD_LEFT);
        $addPayroll->user_id = $request->user_id;
        $addPayroll->duration_time = $request->duration_time;
        $addPayroll->duration_unit = $request->duration_unit;
        $addPayroll->amount_per_unit = $request->amount_per_unit;
        $addPayroll->total_amount = $request->total_amount;
        $addPayroll->total_allowance_amount = $request->total_allowance_amount;
        $addPayroll->total_deduction_amount = $request->total_deduction_amount;
        $addPayroll->gross_amount = $request->gross_amount;
        $addPayroll->due = $request->gross_amount;
        $addPayroll->report_date_ts = date('Y-m-d H:i:s');
        $addPayroll->date = date('d-m-Y');
        $addPayroll->month = $request->month;
        $addPayroll->year = $request->year;
        $addPayroll->admin_id = auth()->user()->id;
        $addPayroll->save();

        $allowance_names = $request->allowance_names;
        $al_amount_types = $request->al_amount_types;
        $allowance_percents = $request->allowance_percents;
        $allowance_amounts = $request->allowance_amounts;

        if ($request->allowance_amounts != null) {
            foreach ($allowance_names as $key => $allowance_name) {
                if ($allowance_amounts[$key] > 0) {
                    $addPayrollAllowance = new PayrollAllowance();
                    $addPayrollAllowance->payroll_id = $addPayroll->id;
                    $addPayrollAllowance->allowance_name = $allowance_name;
                    $addPayrollAllowance->amount_type = $al_amount_types[$key];
                    $al_percent = $allowance_percents[$key] ? $allowance_percents[$key] : 0;
                    $addPayrollAllowance->allowance_percent =  $al_amount_types[$key] == 2 ? $al_percent : 0;
                    $addPayrollAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                    $addPayrollAllowance->save();
                }
            }
        }

        $deduction_names = $request->deduction_names;
        $de_amount_types = $request->de_amount_types;
        $deduction_percents = $request->deduction_percents;
        $deduction_amounts = $request->deduction_amounts;
        if ($request->deduction_amounts != null) {
            foreach ($deduction_names as $key => $deduction_name) {

                if ($deduction_amounts[$key] > 0) {

                    $addPayrollDeduction = new PayrollDeduction();
                    $addPayrollDeduction->payroll_id = $addPayroll->id;
                    $addPayrollDeduction->deduction_name = $deduction_name;
                    $addPayrollDeduction->amount_type = $de_amount_types[$key];
                    $de_percent = $deduction_percents[$key] ? $deduction_percents[$key] : 0;
                    $addPayrollDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $de_percent : 0;
                    $addPayrollDeduction->deduction_amount = $deduction_amounts[$key] ? $deduction_amounts[$key] : 0;
                    $addPayrollDeduction->save();
                }
            }
        }
        session()->flash('successMsg', 'Payroll created successfully');
        return response()->json('Payroll created successfully');
    }

    // Payroll Edit view
    public function edit($payrollId)
    {
        $payroll = Payroll::with(['employee', 'allowances', 'deductions'])->where('id', $payrollId)->first();
        return view('hrm.payroll.edit', compact('payroll'));
    }

    // salary Update
    public function update(Request $request, $salaryId)
    {
        $this->validate($request, [
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ]);

        $updatePayroll = Payroll::with(['allowances', 'deductions'])->where('id', $salaryId)->first();
        $updatePayroll->duration_time = $request->duration_time;
        $updatePayroll->duration_unit = $request->duration_unit;
        $updatePayroll->amount_per_unit = $request->amount_per_unit;
        $updatePayroll->total_amount = $request->total_amount;
        $updatePayroll->total_allowance_amount = $request->total_allowance_amount;
        $updatePayroll->total_deduction_amount = $request->total_deduction_amount;
        $updatePayroll->gross_amount = $request->gross_amount;
        $updatePayroll->due = $request->gross_amount - $updatePayroll->paid;
        $updatePayroll->save();

        foreach ($updatePayroll->allowances as $allowance) {

            $allowance->is_delete_in_update = 1;
            $allowance->save();
        }

        foreach ($updatePayroll->deductions as $deduction) {

            $deduction->is_delete_in_update = 1;
            $deduction->save();
        }

        $allowance_id = $request->payroll_allowance_id;
        $allowance_names = $request->allowance_names;
        $al_amount_types = $request->al_amount_types;
        $allowance_percents = $request->allowance_percents;
        $allowance_amounts = $request->allowance_amounts;

        if ($request->allowance_amounts != null) {

            foreach ($allowance_names as $key => $allowance_name) {

                $salaryAllowance = PayrollAllowance::where('id', $allowance_id[$key])->first();
                if ($salaryAllowance) {

                    $salaryAllowance->allowance_name = $allowance_name;
                    $salaryAllowance->amount_type = $al_amount_types[$key];
                    $al_percent = $allowance_percents[$key] ? $allowance_percents[$key] : 0;
                    $salaryAllowance->allowance_percent = $al_amount_types[$key] == 2 ? $al_percent : 0;
                    $salaryAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                    $salaryAllowance->is_delete_in_update = 0;
                    $salaryAllowance->save();
                } else {

                    if ($allowance_name || $allowance_amounts[$key]) {

                        $addSalaryAllowance = new PayrollAllowance();
                        $addSalaryAllowance->payroll_id = $updatePayroll->id;
                        $addSalaryAllowance->allowance_name = $allowance_name;
                        $addSalaryAllowance->amount_type = $al_amount_types[$key];
                        $al_percent = $allowance_percents[$key] ? $allowance_percents[$key] : 0;
                        $addSalaryAllowance->allowance_percent = $al_amount_types[$key] == 2 ? $al_percent : 0;
                        $addSalaryAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                        $addSalaryAllowance->save();
                    }
                }
            }
        }

        $deduction_id = $request->payroll_deduction_id;
        $deduction_names = $request->deduction_names;
        $de_amount_types = $request->de_amount_types;
        $deduction_percents = $request->deduction_percents;
        $deduction_amounts = $request->deduction_amounts;

        if ($request->deduction_amounts != null) {

            foreach ($deduction_names as $key => $deduction_name) {

                $salaryDeduction = PayrollDeduction::where('id', $deduction_id[$key])->first();
                if ($salaryDeduction) {

                    $salaryDeduction->deduction_name = $deduction_name;
                    $salaryDeduction->amount_type = $de_amount_types[$key];
                    $d_percent = $deduction_percents[$key] ? $deduction_percents[$key] : 0;
                    $salaryDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $d_percent : 0;
                    $salaryDeduction->deduction_amount = $deduction_amounts[$key] ? $deduction_amounts[$key] : 0;
                    $salaryDeduction->is_delete_in_update = 0;
                    $salaryDeduction->save();
                } else {

                    if ($deduction_name || $deduction_amounts[$key]) {

                        $addSalaryDeduction = new PayrollDeduction();
                        $addSalaryDeduction->payroll_id = $updatePayroll->id;
                        $addSalaryDeduction->deduction_name = $deduction_name;
                        $addSalaryDeduction->amount_type = $de_amount_types[$key];
                        $d_percent = $deduction_percents[$key] ? $deduction_percents[$key] : 0;
                        $addSalaryDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $d_percent : 0;
                        $addSalaryDeduction->deduction_amount = $deduction_amounts[$key] ? $deduction_amounts[$key] : 0;
                        $addSalaryDeduction->save();
                    }
                }
            }
        }

        $allowances = PayrollAllowance::where('is_delete_in_update', 1)->get();
        if (count($allowances)) {

            foreach ($allowances as $allowance) {

                $allowance->delete();
            }
        }

        $deductions = PayrollDeduction::where('is_delete_in_update', 1)->get();
        if (count($deductions)) {

            foreach ($deductions as $deduction) {

                $deduction->delete();
            }
        }

        session()->flash('successMsg', 'Payroll updated successfully.');
        return response()->json('Payroll updated successfully.');
    }

    // Show payroll method
    public function show($payrollId)
    {
        $payroll = Payroll::with(['employee', 'allowances', 'deductions'])->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.show', compact('payroll'));
    }

    // Payroll delete method
    public function delete(Request $request, $payrollId)
    {
        $deletePayroll = Payroll::find($payrollId);
        if (!is_null($deletePayroll)) {
            $deletePayroll->delete();
        }
        return response()->json('Payroll deleted successfully.');
    }

    public function paymentView($payrollId)
    {
        $payroll = Payroll::with('payments', 'payments.paymentMethod', 'payments.account', 'employee', 'employee.branch')->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.view_payment', compact('payroll'));
    }

    // Get payment list **requested by ajax**
    public function payment($payrollId)
    {
        $payroll = Payroll::with('employee', 'employee.branch')->where('id', $payrollId)->first();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(
                [
                    'accounts.id',
                    'accounts.name',
                    'accounts.account_number',
                    'accounts.account_type',
                    'accounts.balance'
                ]
            );

        return view('hrm.payroll.ajax_view.add_payment', compact('payroll', 'accounts', 'methods'));
    }

    // Add payment method
    public function addPayment(Request $request, $payrollId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $updatePayroll = Payroll::where('id', $payrollId)->first();

        // Add sale payment
        $addPayrollPayment = new PayrollPayment();
        $addPayrollPayment->reference_no = 'PRP' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('hrm_payroll_payments'), 4, "0", STR_PAD_LEFT);
        $addPayrollPayment->payroll_id = $updatePayroll->id;
        $addPayrollPayment->account_id = $request->account_id;
        $addPayrollPayment->payment_method_id = $request->payment_method_id;
        $addPayrollPayment->paid = $request->paying_amount;
        $addPayrollPayment->due = $updatePayroll->due;
        $addPayrollPayment->date = $request->date;
        $addPayrollPayment->time = date('h:i:s a');
        $addPayrollPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPayrollPayment->month = date('F');
        $addPayrollPayment->year = date('Y');
        $addPayrollPayment->note = $request->note;
        $addPayrollPayment->due = $updatePayroll->due;
        $addPayrollPayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {

            $payrollPaymentAttachment = $request->file('attachment');
            $payrollPaymentAttachmentName = uniqid() . '-' . '.' . $payrollPaymentAttachment->getClientOriginalExtension();
            $payrollPaymentAttachment->move(public_path('uploads/payment_attachment/'), $payrollPaymentAttachmentName);
            $addPayrollPayment->attachment = $payrollPaymentAttachmentName;
        }
        $addPayrollPayment->save();

        // Add bank/cash-in-hand A/C ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 23,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $addPayrollPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $this->payrollUtil->adjustPayrollAmounts($updatePayroll);

        return response()->json('Payment added successfully.');
    }

    // Get payment details **requested by ajax**
    public function paymentDetails($paymentId)
    {
        $payment = PayrollPayment::with('payroll', 'payroll.employee', 'payroll.employee.branch', 'paymentMethod')
            ->where('id', $paymentId)->first();
        return view('hrm.payroll.ajax_view.payment_details', compact('payment'));
    }

    // Payroll payment delete
    public function paymentDelete($paymentId)
    {
        $deletePayrollPayment = PayrollPayment::with('payroll')->where('id', $paymentId)->first();
        $storedAccountId = $deletePayrollPayment->account_id;
        $storedPayroll = $deletePayrollPayment->payroll;

        if (!is_null($deletePayrollPayment)) {

            if ($deletePayrollPayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $deletePayrollPayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $deletePayrollPayment->attachment));
                }
            }

            $deletePayrollPayment->delete();

            if ($storedAccountId) {

                $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
            }

            if ($storedPayroll) {

                $this->payrollUtil->adjustPayrollAmounts($storedPayroll);
            }
        }

        return response()->json('Payment deleted successfully.');
    }

    // Edit payroll payment modal view
    public function paymentEdit($paymentId)
    {
        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(
                [
                    'accounts.id',
                    'accounts.name',
                    'accounts.account_number',
                    'accounts.account_type',
                    'accounts.balance'
                ]
            );

        $payment = PayrollPayment::with('payroll', 'payroll.employee')->where('id', $paymentId)->first();
        return view('hrm.payroll.ajax_view.edit_payment', compact('payment', 'accounts', 'methods'));
    }

    // Update payroll payment
    public function paymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $updatePayrollPayment = PayrollPayment::with('payroll')->where('id', $paymentId)->first();

        // update purchase payment
        $updatePayrollPayment->account_id = $request->account_id;
        $updatePayrollPayment->payment_method_id = $request->payment_method_id;
        $updatePayrollPayment->paid = $request->paying_amount;
        $updatePayrollPayment->date = $request->date;
        $updatePayrollPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updatePayrollPayment->month = date('F');
        $updatePayrollPayment->year = date('Y');
        $updatePayrollPayment->note = $request->note;

        if ($request->hasFile('attachment')) {

            if ($updatePayrollPayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $updatePayrollPayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePayrollPayment->attachment));
                }
            }

            $payrollPaymentAttachment = $request->file('attachment');
            $payrollPaymentAttachmentName = uniqid() . '-' . '.' . $payrollPaymentAttachment->getClientOriginalExtension();
            $payrollPaymentAttachment->move(public_path('uploads/payment_attachment/'), $payrollPaymentAttachmentName);
            $updatePayrollPayment->attachment = $payrollPaymentAttachmentName;
        }

        $updatePayrollPayment->save();

        // Update Sales A/C Ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 23,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $updatePayrollPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        if ($updatePayrollPayment->payroll) {

            $this->payrollUtil->adjustPayrollAmounts($updatePayrollPayment->payroll);
        }

        return response()->json('Payment updated successfully.');
    }

    public function getAllEmployee()
    {
        $employee = DB::table('admin_and_users')->get();
        return response()->json($employee);
    }

    public function getAllDeparment()
    {
        $departments = DB::table('hrm_department')->get();
        return response()->json($departments);
    }

    public function getAllDesignation()
    {
        $designations = DB::table('hrm_designations')->get();
        return response()->json($designations);
    }
}

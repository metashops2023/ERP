<?php

namespace App\Utils;

use Carbon\Carbon;
use App\Utils\Converter;
use App\Models\ExpensePayment;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseUtil
{
    protected $invoiceVoucherRefIdUtil;
    protected $converter;
    protected $accountUtil;
    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        Converter $converter,
        AccountUtil $accountUtil
    ) {
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->accountUtil = $accountUtil;
    }

    public function expenseListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $expenses = '';

        $query = DB::table('expenses')
            ->leftJoin('branches', 'expenses.branch_id', 'branches.id')
            ->leftJoin('admin_and_users', 'expenses.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('expenses.branch_id', NULL);
            } else {

                $query->where('expenses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {

            $query->where('expenses.admin_id', $request->admin_id);
        }

        if ($request->cate_id) {

            $query->where('expenses.category_ids', 'LIKE', '%'. $request->cate_id . '%');
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('expenses.report_date', $date_range); // Final
        }

        $query->select(
            'expenses.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'admin_and_users.prefix as cr_prefix',
            'admin_and_users.name as cr_name',
            'admin_and_users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $expenses = $query->orderBy('expenses.report_date', 'desc');
        } else {

            $expenses = $query->where('expenses.branch_id', auth()->user()->branch_id)
                ->orderBy('expenses.report_date', 'desc');
        }

        return DataTables::of($expenses)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->branch_id == $row->branch_id) :

                        if (auth()->user()->permission->expense['edit_expense'] == '1') :

                            $html .= '<a class="dropdown-item" href="' . route('expenses.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                        endif;

                        if (auth()->user()->permission->expense['delete_expense'] == '1') :

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('expenses.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> '.__("Delete").'</a>';
                        endif;

                        if ($row->due > 0) :

                            $html .= '<a class="dropdown-item" id="add_payment" href="' . route('expenses.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Add Payment").'</a>';
                        endif;
                    endif;

                    $html .= '<a class="dropdown-item" id="view_payment" href="' . route('expenses.payment.view', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> '.__("View Payment").'</a>';



                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })->editColumn('descriptions', function ($row) use ($generalSettings) {

                $expenseDescriptions = DB::table('expense_descriptions')
                    ->where('expense_id', $row->id)
                    ->leftJoin('expense_categories', 'expense_descriptions.expense_category_id', 'expense_categories.id')
                    ->select(
                        'expense_categories.name',
                        'expense_categories.code',
                        'expense_descriptions.amount'
                    )->get();

                $html = '';

                foreach ($expenseDescriptions as $exDescription) {

                    $html .= '<b>' . $exDescription->name . '(' . $exDescription->code . '):</b> ' . $exDescription->amount . '</br>';
                }

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('user_name',  function ($row) {

                return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
            })
            ->editColumn('payment_status',  function ($row) {

                $html = "";
                $payable = $row->net_total_amount;

                if ($row->due <= 0) {

                    $html .= '<span class="badge bg-success">Paid</span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    $html .= '<span class="badge bg-primary text-white">Partial</span>';
                } elseif ($payable == $row->due) {

                    $html .= '<span class="badge bg-danger text-white">Due</span>';
                }
                return $html;
            })
            ->editColumn('tax_percent',  function ($row) {

                return $row->tax_percent . '%';
            })
            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')
            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')
            ->rawColumns(['action', 'date', 'from', 'user_name', 'payment_status', 'tax_percent', 'due', 'net_total_amount', 'descriptions'])
            ->make(true);
    }

    public function categoryWiseExpenseListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $expenses = '';
        $query = DB::table('expense_descriptions')
            ->leftJoin('expenses', 'expense_descriptions.expense_id', 'expenses.id')
            ->leftJoin('expense_categories', 'expense_descriptions.expense_category_id', 'expense_categories.id')
            ->leftJoin('branches', 'expenses.branch_id', 'branches.id')
            ->leftJoin('admin_and_users', 'expenses.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('expenses.branch_id', NULL);
            } else {
                $query->where('expenses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {
            $query->where('expenses.admin_id', $request->admin_id);
        }

        if ($request->category_id) {
            $query->where('expense_descriptions.expense_category_id', $request->category_id);
        }

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $query->whereBetween('expenses.report_date', $date_range); // Final
        }

        $query->select(
            'expense_descriptions.amount',
            'expenses.invoice_id',
            'expenses.date',
            'expense_categories.name',
            'expense_categories.code',
            'branches.name as branch_name',
            'branches.branch_code',
            'admin_and_users.prefix as cr_prefix',
            'admin_and_users.name as cr_name',
            'admin_and_users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $expenses = $query->orderBy('expenses.report_date', 'desc');
        } else {
            $expenses = $query->where('expenses.branch_id', auth()->user()->branch_id)
                ->orderBy('expenses.report_date', 'desc');
        }

        return DataTables::of($expenses)
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })->editColumn('category_name', function ($row) {
                return $row->name . ' (' . $row->code . ')';
            })->editColumn('user_name',  function ($row) {
                if ($row->cr_name) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                } else {
                    return '---';
                }
            })->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')
            ->rawColumns(['date', 'from', 'category_name', 'user_name', 'amount'])
            ->make(true);
    }

    public function adjustExpenseAmount($expense)
    {
        $totalExpensePaid = DB::table('expense_payments')
            ->where('expense_payments.expense_id', $expense->id)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('expense_payments.expense_id')
            ->get();

        $due = $expense->net_total_amount - $totalExpensePaid->sum('total_paid');
        $expense->paid = $totalExpensePaid->sum('total_paid');
        $expense->due = $due;
        $expense->save();

        return $expense;
    }

    public function addPaymentGetId($voucher_prefix, $expense_id, $request, $another_amount = 0)
    {
        $addExpensePayment = new ExpensePayment();
        $addExpensePayment->invoice_id = ($voucher_prefix != null ? $voucher_prefix : 'EPV') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('expense_payments'), 5, "0", STR_PAD_LEFT);
        $addExpensePayment->expense_id = $expense_id;
        $addExpensePayment->account_id = $request->account_id;
        $addExpensePayment->payment_method_id = $request->payment_method_id;
        $addExpensePayment->paid_amount = isset($request->paying_amount) ? $request->paying_amount : $another_amount;
        $addExpensePayment->date = $request->date;
        $addExpensePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addExpensePayment->month = date('F');
        $addExpensePayment->year = date('Y');
        $addExpensePayment->note = $request->payment_note;
        $addExpensePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $expensePaymentAttachment = $request->file('attachment');
            $expensePaymentAttachmentName = uniqid() . '-' . '.' . $expensePaymentAttachment->getClientOriginalExtension();
            $expensePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expensePaymentAttachmentName);
            $addExpensePayment->attachment = $expensePaymentAttachmentName;
        }

        $addExpensePayment->save();
        return $addExpensePayment->id;
    }

    public function updatePayment($expensePayment, $request, $another_amount = 0)
    {
        $expensePayment->account_id = $request->account_id;
        $expensePayment->payment_method_id = $request->payment_method_id;
        $expensePayment->paid_amount = isset($request->paying_amount) ? $request->paying_amount : $another_amount;
        $expensePayment->date = $request->date;
        $expensePayment->report_date = date('Y-m-d', strtotime($request->date));
        $expensePayment->note = $request->payment_note;
        $expensePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            if ($expensePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $expensePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $expensePayment->attachment));
                }
            }
            $expensePaymentAttachment = $request->file('attachment');
            $expensePaymentAttachmentName = uniqid() . '-' . '.' . $expensePaymentAttachment->getClientOriginalExtension();
            $expensePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expensePaymentAttachmentName);
            $expensePayment->attachment = $expensePaymentAttachmentName;
        }

        $expensePayment->save();
    }

    public function expenseDelete($deleteExpense)
    {
        $storedExpenseAccountId = $deleteExpense->expense_account_id;

        $storedExpensePayments = $deleteExpense->expense_payments;

        if (!is_null($deleteExpense)) {

            $deleteExpense->delete();

            if (count($storedExpensePayments) > 0) {

                foreach ($storedExpensePayments as $payment) {

                    if ($payment->attachment) {

                        if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {

                            unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                        }
                    }

                    // Update Bank/Cash-in-hand Balance
                    if ($payment->account_id) {

                        $this->accountUtil->adjustAccountBalance('debit', $payment->account_id);
                    }
                }
            }
        }

        // Update Expense A/C Balance
        if ($storedExpenseAccountId) {

            $this->accountUtil->adjustAccountBalance('credit', $storedExpenseAccountId);
        }
    }
}

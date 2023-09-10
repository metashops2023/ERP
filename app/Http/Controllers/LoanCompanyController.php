<?php

namespace App\Http\Controllers;

use App\Models\LoanCompany;
use App\Utils\AccountUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LoanCompanyController extends Controller
{
    protected $accountUtil;

    public function __construct(AccountUtil $accountUtil)
    {
        $this->accountUtil = $accountUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companies = DB::table('loan_companies')->orderBy('id', 'DESC')
                ->where('branch_id', auth()->user()->branch_id)->get();
            $generalSettings = DB::table('general_settings')->first();
            return DataTables::of($companies)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__("Action").' </button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item" href="' . route('accounting.loan.companies.edit', [$row->id]) . '" id="edit_company"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                        $html .= '<a class="dropdown-item" href="' . route('accounting.loan.payment.list', [$row->id]) . '" id="view_payments"><i class="far fa-edit text-primary"></i> '.__("View Payments").'</a>';

                        if ($row->pay_loan_due > 0) {
                            $html .= '<a class="dropdown-item" id="loan_payment" href="' . route('accounting.loan.advance.receive.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Loan & Advance Due Receive").'</a>';
                        }

                        if ($row->get_loan_due > 0) {
                            $html .= '<a class="dropdown-item" id="loan_payment" href="' . route('accounting.loan.liability.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("Loan Liability Due Payment").' </a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete_company" href="' . route('accounting.loan.companies.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> '.__("Delete").'</a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })->editColumn('pay_loan_amount', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->pay_loan_amount . '<br/>Due : ' . json_decode($generalSettings->business, true)['currency'] . ' <span class="text-danger">' . $row->pay_loan_due . '</span>';
                })->editColumn('get_loan_amount', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->get_loan_amount . '<br/>Due : ' . json_decode($generalSettings->business, true)['currency'] . ' <span class="text-danger">' . $row->get_loan_due . '</span>';
                })->editColumn('total_pay', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_pay;
                })->editColumn('total_receive', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_receive;
                })
                ->rawColumns(['pay_loan_amount', 'get_loan_amount', 'action'])->smart(true)->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required'
        ]);

        $addCompany = new LoanCompany();
        $addCompany->name = $request->name;
        $addCompany->phone = $request->phone;
        $addCompany->address = $request->address;
        $addCompany->branch_id = auth()->user()->branch_id;
        $addCompany->save();

        return response()->json(__('Company created Successfully'));

    }

    public function edit($companyId)
    {
        $company = DB::table('loan_companies')->where('id', $companyId)->first();
        return view('accounting.loans.ajax_view.editCompany', compact('company'));
    }

    public function update(Request $request, $companyId)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required'
        ]);

        $updateCompany =  LoanCompany::where('id', $companyId)->first();
        $updateCompany->name = $request->name;
        $updateCompany->phone = $request->phone;
        $updateCompany->address = $request->address;
        $updateCompany->save();
        return response()->json(__('Company updated Successfully'));

    }

    public function delete(Request $request, $companyId)
    {
        $deleteCompany = LoanCompany::with(['loans', 'loanPayments'])->where('id', $companyId)->first();
        $storedCompanyLoans = $deleteCompany->loans;
        $storedCompanyLoanPayments = $deleteCompany->loanPayments;
        if (!is_null($deleteCompany)) {
            $deleteCompany->delete();

            foreach ($storedCompanyLoanPayments as $companyLoanPayment) {
                // Adjust Bank/Cash-In-Hand A/C balance
                $this->accountUtil->adjustAccountBalance('debit', $companyLoanPayment->account_id);
            }

            foreach ($storedCompanyLoans as $companyLoan) {
                if ($companyLoan->type == 1) {

                    if ($companyLoan->loan_account_id) {
                        // Adjust Loan A/C balance
                        $this->accountUtil->adjustAccountBalance('debit', $companyLoan->loan_account_id);
                    }

                    if ($companyLoan->account_id) {
                        // Adjust Bank/Cash-In-Hand A/C balance
                        $this->accountUtil->adjustAccountBalance('debit', $companyLoan->account_id);
                    }
                } else {

                    if ($companyLoan->loan_account_id) {
                        // Adjust Loan A/C balance
                        $this->accountUtil->adjustAccountBalance('credit', $companyLoan->loan_account_id);
                    }

                    if ($companyLoan->account_id) {
                        // Adjust Bank/Cash-In-Hand A/C balance
                        $this->accountUtil->adjustAccountBalance('debit', $companyLoan->account_id);
                    }
                }
            }
        }

        return response()->json(__('Company deleted Successfully'));
    }
}

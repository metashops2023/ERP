<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Utils\Util;
use App\Models\Contra;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;

class ContraController extends Controller
{
    protected $accountUtil;
    protected $util;
    protected $converter;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        AccountUtil $accountUtil,
        Util $util,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->util = $util;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->select('business')->first();

            $contras = '';

            $query = DB::table('contras')
                ->leftJoin('accounts as receiver_accounts', 'contras.receiver_account_id', 'receiver_accounts.id')
                ->leftJoin('accounts as sender_accounts', 'contras.sender_account_id', 'sender_accounts.id')
                ->leftJoin('branches', 'contras.branch_id', 'branches.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('contras.branch_id', NULL);
                } else {

                    $query->where('contras.branch_id', $request->branch_id);
                }
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('contras.report_date', $date_range); // Final
            }

            $query->select(
                'contras.id',
                'contras.date',
                'contras.voucher_no',
                'contras.amount',
                'contras.remarks',
                'receiver_accounts.name as receiver_account_name',
                'receiver_accounts.account_number as receiver_account_no',
                'receiver_accounts.account_type as receiver_account_type',
                'sender_accounts.name as sender_account_name',
                'sender_accounts.account_number as sender_account_no',
                'sender_accounts.account_type as sender_account_type',
                'branches.name as branch_name',
                'branches.branch_code',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $contras = $query->orderBy('contras.report_date', 'desc');
            } else {

                $contras = $query->where('contras.branch_id', auth()->user()->branch_id)
                    ->orderBy('contras.report_date', 'desc');
            }

            return DataTables::of($contras)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . ' </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.contras.show', [$row->id]) . '" id="show"><i class="far fa-eye me-1 text-primary"></i>' . __("Show") . ' </a>';
                    $html .= '<a class="dropdown-item" id="edit" href="' . route('accounting.contras.edit', [$row->id]) . '"><i class="far fa-edit me-1 text-primary"></i>' . __("Edit") . ' </a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('accounting.contras.delete', [$row->id]) . '"><i class="far fa-trash-alt me-1 text-primary"></i>' . __("Delete") . ' </a>';

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($settings) {

                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);
                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('receiver_account', function ($row) {

                    $__ac = $row->receiver_account_type == 2 ? '(A/C:' . $row->receiver_account_no . ')' : '(Cash-In-Hand)';
                    return $row->receiver_account_name . $__ac;
                })
                ->editColumn('sender_account', function ($row) {

                    $__ac = $row->sender_account_type == 2 ? '(A/C:' . $row->sender_account_no . ')' : '(Cash-In-Hand)';
                    return $row->sender_account_name . $__ac;
                })
                ->editColumn('branch', function ($row) use ($settings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($settings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')
                ->rawColumns(['action', 'date', 'receiver_account', 'sender_account', 'branch', 'amount'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('accounting.contra.index', compact('branches'));
    }

    public function create()
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        return view('accounting.contra.ajax_view.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'date' => 'required|date',
            'receiver_account_id' => 'required',
            'sender_account_id' => 'required',
            'amount' => 'required|numeric',
        ], [
            'sender_account_id.required' => 'Sender A/c is required.',
            'receiver_account_id.required' => 'Receiver A/C is required.',
        ]);

        $addContraGetId = Contra::insertGetId([
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'voucher_no' => $request->voucher_no
                ? $request->voucher_no
                : 'CO' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('contras'), 5, "0", STR_PAD_LEFT),
            'date' => $request->date,
            'report_date' => date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
            'sender_account_id' => $request->sender_account_id,
            'receiver_account_id' => $request->receiver_account_id,
            'amount' => $request->amount,
            'remarks' => $request->remarks,
        ]);

        // Add Sender A/C ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 27,
            date: $request->date,
            account_id: $request->sender_account_id,
            trans_id: $addContraGetId,
            amount: $request->amount,
            balance_type: 'debit'
        );

        // Add Receiver A/C ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 26,
            date: $request->date,
            account_id: $request->receiver_account_id,
            trans_id: $addContraGetId,
            amount: $request->amount,
            balance_type: 'debit'
        );

        return response()->json('Contra created successfully');
    }

    public function edit($contraId)
    {
        $contra = DB::table('contras')->where('id', $contraId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        return view('accounting.contra.ajax_view.edit', compact('contra', 'accounts'));
    }

    public function update(Request $request, $contraId)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'sender_account_id' => 'required',
            'receiver_account_id' => 'required',
            'amount' => 'required|numeric',
        ], [
            'sender_account_id.required' => 'Sender A/c is required.',
            'receiver_account_id.required' => 'Receiver A/C is required.',
        ]);

        Contra::where('id', $contraId)->update([
            'voucher_no' => $request->voucher_no
                ? $request->voucher_no
                : 'CO' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('contras'), 5, "0", STR_PAD_LEFT),
            'date' => $request->date,
            'report_date' => date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
            'sender_account_id' => $request->sender_account_id,
            'receiver_account_id' => $request->receiver_account_id,
            'amount' => $request->amount,
            'remarks' => $request->remarks,
        ]);

        // Update Sender A/C ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 27,
            date: $request->date,
            account_id: $request->sender_account_id,
            trans_id: $contraId,
            amount: $request->amount,
            balance_type: 'debit'
        );

        // Update Receiver A/C ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 26,
            date: $request->date,
            account_id: $request->receiver_account_id,
            trans_id: $contraId,
            amount: $request->amount,
            balance_type: 'debit'
        );

        return response()->json('Contra updated successfully');
    }

    public function show($contraId)
    {
        $contra = Contra::with(
          [
              'branch',
              'user',
              'senderAccount',
              'senderAccount.bank',
              'receiverAccount',
              'receiverAccount.bank'
          ]
        )->where('id', $contraId)->first();

        return view('accounting.contra.ajax_view.show', compact('contra'));
    }

    public function delete(Request $request, $contraId)
    {
        $deleteContra = Contra::where('id', $contraId)->first();

        $storedSenderAccountId = $deleteContra->sender_account_id;
        $storedReceiverAccountId = $deleteContra->receiver_account_id;

        if (!is_null($deleteContra)) {
            $deleteContra->delete();

            if ($storedSenderAccountId) {
                // Adjust A/C balance
                $this->accountUtil->adjustAccountBalance(balanceType: 'debit', account_id: $storedSenderAccountId);
            }

            if ($storedReceiverAccountId) {
                // Adjust A/C balance
                $this->accountUtil->adjustAccountBalance(balanceType: 'debit', account_id: $storedReceiverAccountId);
            }
        }

        return response()->json('Contra deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Utils\SaleUtil;
use App\Utils\Converter;
use App\Models\SaleReturn;
use App\Utils\AccountUtil;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Utils\CustomerUtil;
use Illuminate\Http\Request;
use App\Utils\NameSearchUtil;
use App\Utils\ProductStockUtil;
use App\Models\SaleReturnProduct;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnController extends Controller
{
    protected $productStockUtil;
    protected $saleUtil;
    protected $nameSearchUtil;
    protected $accountUtil;
    protected $customerUtil;
    protected $converter;
    protected $invoiceVoucherRefIdUtil;
    protected $userActivityLogUtil;

    public function __construct(
        ProductStockUtil $productStockUtil,
        SaleUtil $saleUtil,
        NameSearchUtil $nameSearchUtil,
        AccountUtil $accountUtil,
        CustomerUtil $customerUtil,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {

        $this->productStockUtil = $productStockUtil;
        $this->saleUtil = $saleUtil;
        $this->nameSearchUtil = $nameSearchUtil;
        $this->accountUtil = $accountUtil;
        $this->customerUtil = $customerUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->sale['return_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $returns = '';
            $generalSettings = DB::table('general_settings')->first();
            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
                ->leftJoin('customers', 'sale_returns.customer_id', 'customers.id');

            $query->select(
                'sale_returns.*',
                'sales.invoice_id as parent_invoice_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as cus_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $returns = $query->orderBy('id', 'desc');
            } else {

                $returns = $query->where('sale_returns.branch_id', auth()->user()->branch_id)
                    ->orderBy('report_date', 'desc');
            }

            return DataTables::of($returns)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('sales.returns.show', $row->id) . '"><i class="far fa-eye mr-1 text-primary"></i> '.__("View").'</a>';

                        if (auth()->user()->branch_id == $row->branch_id) {

                            $html .= '<a class="dropdown-item" href="' . route('sale.return.random.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> '.__("Edit").'</a>';
                            $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.returns.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> '.__("Delete").'</a>';

                            $html .= '<a class="dropdown-item" id="view_payment" href="' . route('sales.returns.payment.list', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> '.__("View Payment").'</a>';

                            if ($row->total_return_due > 0 && $row->sale_id) {

                                if (auth()->user()->permission->sale['sale_payment'] == '1') {

                                    $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->sale_id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Pay Return Amt.").'</a>';
                                }

                        }

                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {

                    return $row->branch_name != null ? ($row->branch_name . '/' . $row->branch_code) . '<b>(BL)</b>' : json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                })
                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount text-danger" data-value="' . $row->total_return_amount . '">' . $this->converter->format_in_bdt($row->total_return_amount) . '</span>')

                ->editColumn('total_return_due_pay', fn ($row) => '<span class="total_return_due_pay text-success" data-value="' . $row->total_return_due_pay . '">' . $this->converter->format_in_bdt($row->total_return_due_pay) . '</span>')

                // ->editColumn('total_return_due', function ($row) {

                //     if ($row->sale_id) {

                //         if ($row->total_return_due >= 0) {

                //             return '<span class="text-danger">' . $this->converter->format_in_bdt($row->total_return_due) . '</span>';
                //         }
                //     }else {

                //         return '<span class="text-danger"><b>CHECK CUSTOMER DUE</b></span>';
                //     }
                // })

                // ->editColumn('payment_status', function ($row) {

                //     if ($row->sale_id) {

                //         if ($row->total_return_due > 0) {

                //             return '<span class="text-danger"><b>Due</b></span>';
                //         } else {

                //             return '<span class="text-success"><b>Paid</b></span>';
                //         }
                //     }else{

                //         return '<span class="text-danger"><b>CHECK CUSTOMER DUE</b></span>';
                //     }

                // })

                ->editColumn('customer', function ($row) {

                    return $row->cus_name ? $row->cus_name : 'Walk-In-Customer';
                })
                ->rawColumns(['action', 'date', 'from', 'total_return_amount', 'total_return_due_pay'])
                ->make(true);
        }

        return view('sales.sale_return.index');
    }

    // Show Sale return details
    public function show($returnId)
    {
        $saleReturn = SaleReturn::with([
            'sale',
            'customer',
            'branch',
            'sale_return_products',
            'sale_return_products.product',
            'sale_return_products.variant',
        ])->where('id', $returnId)->first();

        return view('sales.sale_return.ajax_view.show', compact('saleReturn'));
    }

    //Deleted sale return
    public function delete($saleReturnId)
    {
        $saleReturn = SaleReturn::with(['sale', 'customer', 'sale_return_products'])->where('id', $saleReturnId)->first();

        $storedReturnedProducts = $saleReturn->sale_return_products;
        $storedReturnAccountId = $saleReturn->sale_return_account_id;
        $storedBranchId = $saleReturn->branch_id;

        if ($saleReturn->total_return_due_pay > 0) {

            return response()->json(['errorMsg' => "You can not delete this return invoice, Cause your have paid some or full amount on this return."]);
        }

        // Add User Activity Log
        $this->userActivityLogUtil->addLog(
            action: 3,
            subject_type: 9,
            data_obj: $saleReturn
        );

        $saleReturn->delete();

        foreach ($storedReturnedProducts as $return_product) {

            $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);
            $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $storedBranchId);
        }

        if ($saleReturn->sale) {

            $saleReturn->sale->is_return_available = 0;
            $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
        }

        if ($saleReturn->customer_id) {

            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($saleReturn->customer_id);
        }

        if ($storedReturnAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedReturnAccountId);
        }


            return response()->json(__('Sale return deleted successfully'));


    }

    public function returnPaymentList($returnId)
    {
        $return = SaleReturn::with([
            'sale',
            'payments',
            'payments.paymentMethod',
            'payments.account',
            'payments.paymentMethod',
            'customer',
            'branch'
        ])->where('id', $returnId)->first();

        return view('sales.sale_return.ajax_view.return_payment_list', compact('return'));
    }
}

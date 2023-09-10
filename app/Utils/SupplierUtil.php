<?php

namespace App\Utils;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierUtil
{
    public function supplierListTable($request)
    {
        $branchWiseSupplierAmountUtil =  new \App\Utils\BranchWiseSupplierAmountsUtil();

        if (auth()->user()->role_type == 1) {
            if (isset($request->branch_id) && $request->branch_id != null) {
                $suppliers = DB::table('suppliers')->where('branch_id', $request->branch_id);
            } else {
                $suppliers = DB::table('suppliers');
            }
        } else if (auth()->user()->role_type == 2) {
            if (isset($request->branch_id) && $request->branch_id != null) {
                $suppliers = DB::table('suppliers')->where('admin_user_id', auth()->user()->id)
                    ->where('branch_id', $request->branch_id);
            } else {
                $suppliers = DB::table('suppliers')->where('admin_user_id', auth()->user()->id);
            }
        } else {
            $suppliers = DB::table('suppliers')->where('branch_id', auth()->user()->branch_id);
        }

        return DataTables::of($suppliers)

            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . route('contacts.supplier.view', [$row->id]) . '"><i class="fas fa-tasks text-primary"></i>'.__("Manage").' </a>';

                    if (auth()->user()->permission->contact['supplier_edit'] == '1') :

                        $html .= '<a class="dropdown-item" href="' . route('contacts.supplier.edit', [$row->id]) . '" id="edit"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                    endif;

                    // if (auth()->user()->permission->contact['supplier_delete'] == '1') :

                    //     $html .= '<a class="dropdown-item" id="delete" href="' . route('contacts.supplier.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    // endif;

                    if ($row->status == 1) :

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.supplier.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i> '.__("Change Status").'</a>';
                    else :

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.supplier.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i> '.__("Change Status").'</a>';
                    endif;




                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })

            ->editColumn('business_name', function ($row) {

                return $row->business_name ? $row->business_name : '...';
            })

            ->editColumn('tax_number', function ($row) {

                return $row->tax_number ? $row->tax_number : '...';
            })

            ->editColumn('opening_balance', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $openingBalance = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['opening_balance'];
                return '<span class="opening_balance" data-value="' . $openingBalance . '">' . \App\Utils\Converter::format_in_bdt($openingBalance) . '</span>';
            })

            ->editColumn('total_purchase', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $totalPurchase = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['total_purchase'];
                return '<span class="total_purchase" data-value="' . $totalPurchase . '">' . \App\Utils\Converter::format_in_bdt($totalPurchase) . '</span>';
            })

            ->editColumn('total_paid', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $totalPaid = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['total_paid'];
                return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';
            })

            ->editColumn('total_purchase_due', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $totalPurchaseDue = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['total_purchase_due'];
                return '<span class="total_purchase_due" data-value="' . $totalPurchaseDue . '">' . \App\Utils\Converter::format_in_bdt($totalPurchaseDue) . '</span>';
            })

            ->editColumn('total_return', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $totalReturn = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['total_return'];
                return '<span class="total_return" data-value="' . $totalReturn . '">' . \App\Utils\Converter::format_in_bdt($totalReturn) . '</span>';
            })

            ->editColumn('total_purchase_return_due', function ($row) use ($request, $branchWiseSupplierAmountUtil) {

                $totalPurchaseReturnDue = $branchWiseSupplierAmountUtil->branchWiseSupplierAmount($row->id, $request->branch_id)['total_purchase_return_due'];
                return '<span class="total_purchase_return_due" data-value="' . $totalPurchaseReturnDue . '">' . \App\Utils\Converter::format_in_bdt($totalPurchaseReturnDue) . '</span>';
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
            ->rawColumns(['action', 'business_name', 'tax_number', 'opening_balance', 'total_purchase', 'total_paid', 'total_purchase_due', 'total_return', 'total_purchase_return_due', 'status'])
            ->make(true);
    }

    public function supplierPurchaseList($request, $supplierId)
    {
        $generalSettings = DB::table('general_settings')->first();
        $purchases = '';
        $query = DB::table('purchases')
            ->where('purchases.supplier_id', $supplierId)
            ->where('purchases.is_purchased', 1)
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', NULL);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query;
        } else {

            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $purchases = $query->select(
            'purchases.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';

        if (auth()->user()->permission->purchase['purchase_edit'] == '1') {

            $html .= '<a class="dropdown-item" href="' . route('purchases.edit', [$row->id, 'purchased']) . ' "><i class="far fa-edit text-primary"></i>'.__("Edit").' </a>';
        }

        if (auth()->user()->permission->purchase['purchase_delete'] == '1') {

            $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i>'.__("Delete").' </a>';
        }

        $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> '.__("Barcode").'</a>';

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->permission->purchase['purchase_payment'] == '1') {

                if ($row->due > 0) {

                    $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("Add Payment").' </a>';
                }

                if ($row->purchase_return_due > 0) {

                    $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Receive Return Amount").'</a>';
                }
            }
        }

        $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("View Payment").' </a>';

        if (auth()->user()->permission->purchase['purchase_return'] == '1') {

            $html .= '<a class="dropdown-item" id="purchase_return" href="' . route('purchases.returns.create', $row->id) . '"><i class="fas fa-undo-alt text-primary"></i>'.__("Purchase Return").' </a>';
        }

        $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope mr-1 text-primary"></i>'.__("Items Received Notification").' </a>';


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
                if ($row->warehouse_name) {

                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {

                    return $row->branch_name . '<b>(BL)</b>';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                }
            })

            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

            ->editColumn('return_amount', fn ($row) => '<span class="return_amount" data-value="' . $row->purchase_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->purchase_return_amount) . '</span>')

            ->editColumn('return_due', fn ($row) => '<span class="return_due text-danger" data-value="' . $row->purchase_return_due . '">' . \App\Utils\Converter::format_in_bdt($row->purchase_return_due) . '</span>')

            ->editColumn('status', function ($row) {

                if ($row->purchase_status == 1) {

                    return '<span class="text-success"><b>Purchased</b></span>';
                } elseif ($row->purchase_status == 2) {

                    return '<span class="text-secondary"><b>Pending</b></span>';
                } elseif ($row->purchase_status == 3) {

                    return '<span class="text-primary"><b>Purchased By Order</b></span>';
                }
            })

            ->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="badge bg-success">Paid</span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="badge bg-primary text-white">Partial</span>';
                } elseif ($payable == $row->due) {

                    return '<span class="badge bg-danger text-white">Due</span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'return_amount', 'return_due', 'payment_status', 'status', 'created_by'])

            ->make(true);
    }

    public function supplierLedgers($request, $supplierId)
    {
        $settings = DB::table('general_settings')->first();

        $supplierLedgers = '';

        $query = DB::table('supplier_ledgers')->where('supplier_ledgers.supplier_id', $supplierId)
            ->leftJoin('branches', 'supplier_ledgers.branch_id', 'branches.id')
            ->leftJoin('purchases', 'supplier_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'supplier_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
            ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
            ->leftJoin('purchases as agp_purchase', 'purchase_payments.purchase_id', 'agp_purchase.id')
            ->select(
                'supplier_ledgers.report_date',
                'supplier_ledgers.voucher_type',
                'supplier_ledgers.debit',
                'supplier_ledgers.credit',
                'supplier_ledgers.running_balance',
                'branches.name as b_name',
                'purchases.invoice_id as purchase_inv_id',
                'purchases.purchase_note as purchase_par',
                'purchase_returns.invoice_id as return_inv_id',
                'purchase_returns.date as purchase_return_par',
                'purchase_payments.invoice_id as payment_voucher_no',
                'purchase_payments.note as purchase_payment_par',
                'supplier_payments.voucher_no as supplier_payment_voucher',
                'supplier_payments.less_amount',
                'supplier_payments.note as supplier_payment_par',
                'agp_purchase.invoice_id as agp_purchase',
            )->orderBy('supplier_ledgers.report_date', 'asc');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('supplier_ledgers.branch_id', NULL);
            } else {

                $query->where('supplier_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->voucher_type) {

            $query->where('supplier_ledgers.voucher_type', $request->voucher_type); // Final
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('supplier_ledgers.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $supplierLedgers = $query->orderBy('supplier_ledgers.report_date', 'asc');
        } else {

            $supplierLedgers = $query->where('supplier_ledgers.branch_id', auth()->user()->branch_id)
                ->orderBy('supplier_ledgers.report_date', 'asc');
        }

        $supplierLedgers = $supplierLedgers->get();
        $tempRunning = 0;
        foreach ($supplierLedgers as $supplierLedger) {

            $supplierLedger->running_balance =  $tempRunning  + ($supplierLedger->credit - ($supplierLedger->debit + $supplierLedger->less_amount));
            $tempRunning = $supplierLedger->running_balance;
        }

        return DataTables::of($supplierLedgers)
            ->editColumn('date', function ($row) use ($settings) {

                $dateFormat = json_decode($settings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);
                return date($__date_format, strtotime($row->report_date));
            })

            ->editColumn('particulars', function ($row) {

                $type = $this->voucherType($row->voucher_type);
                $__agp = $row->agp_purchase ? '/' . 'AGP:<b>' . $row->agp_purchase . '</b>' : '';
                $__less = $row->less_amount > 0 ? '/' . 'Less:(<b class="text-success">' . $row->less_amount . '</b>)' : '';
                return '<b>' . $type['name'] . '</b>' . $__agp . $__less . ($row->{$type['par']} ? '/' . $row->{$type['par']} : '');
            })

            ->editColumn('b_name', function ($row) use ($settings) {

                if ($row->b_name) {

                    return $row->b_name;
                } else {

                    return json_decode($settings->business, true)['shop_name'];
                }
            })

            ->editColumn('voucher_no',  function ($row) {

                $type = $this->voucherType($row->voucher_type);
                return $row->{$type['voucher_no']};
            })

            ->editColumn('debit', fn ($row) => '<span class="debit" data-value="' . $row->debit . '">' . \App\Utils\Converter::format_in_bdt($row->debit) . '</span>')

            ->editColumn('credit', fn ($row) => '<span class="credit" data-value="' . $row->credit . '">' . \App\Utils\Converter::format_in_bdt($row->credit) . '</span>')

            ->editColumn('running_balance', fn ($row) => '<span class="running_balance">' . \App\Utils\Converter::format_in_bdt($row->running_balance) . '</span>')

            ->rawColumns(['date', 'particulars', 'b_name', 'voucher_no', 'debit', 'credit', 'running_balance'])
            ->make(true);
    }

    public function uncompletedPurchaseOrderList($request, $supplierId)
    {
        $generalSettings = DB::table('general_settings')->first();
        $purchases = '';
        $query = DB::table('purchases')
            ->where('purchases.supplier_id', $supplierId)
            ->where('purchases.purchase_status', 3)
            ->where('purchases.po_receiving_status', '!=', 'Completed')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');

        $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.total_purchase_amount',
            'purchases.due',
            'purchases.paid',
            'purchases.po_qty',
            'purchases.po_pending_qty',
            'purchases.po_received_qty',
            'purchases.po_receiving_status',
            'purchases.purchase_return_amount',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', NULL);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchases = $query->orderBy('purchases.report_date', 'desc');
        } else {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchases)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item details_button" href="' . route('purchases.show.order', [$row->id]) . '"><i class="far fa-eye text-primary"></i>'.__("View").' </a>';

        if (auth()->user()->branch_id == $row->branch_id) {

            $html .= '<a class="dropdown-item" href="' . route('purchases.po.receive.process', [$row->id]) . '"><i class="fas fa-check-double text-primary"></i>'.__("PO To Receive").' </a>';
        }

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->permission->purchase['purchase_payment'] == '1') {

                if ($row->due > 0) {

                    $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Payment").'</a>';
                }

                $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("View Payments").'</a>';
            }

            if (auth()->user()->permission->purchase['purchase_edit'] == '1') {

                $html .= '<a class="dropdown-item" href="' . route('purchases.edit', [$row->id, 'ordered']) . ' "><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
            }

            if (auth()->user()->permission->purchase['purchase_delete'] == '1') {

                $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i>'.__("Action").' </a>';
            }
        }



                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {

                    return $row->branch_name . '<b>(BL)</b>';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                }
            })

            ->editColumn('po_qty', fn ($row) => '<span class="po_qty" data-value="' . $row->po_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_qty) . '</span>')

            ->editColumn('po_received_qty', fn ($row) => '<span class="po_received_qty text-success" data-value="' . $row->po_received_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_received_qty) . '</span>')

            ->editColumn('po_pending_qty', fn ($row) => '<span class="po_pending_qty text-danger" data-value="' . $row->po_pending_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_pending_qty) . '</span>')

            ->editColumn('total_purchase_amount', fn ($row) => '<span class="po_total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="po_paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="po_due" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span></span>')

            ->editColumn('po_receiving_status', function ($row) {

                if ($row->po_receiving_status == 'Completed') {

                    return '<span class="text-success"><b>Completed</b></span>';
                } elseif ($row->po_receiving_status == 'Pending') {

                    return '<span class="text-danger"><b>Pending</b></span>';
                } elseif ($row->po_receiving_status == 'Partial') {

                    return '<span class="text-primary"><b>Partial</b></span>';
                }
            })
            ->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'from', 'total_purchase_amount', 'po_qty', 'po_received_qty', 'po_pending_qty', 'paid', 'due', 'payment_status', 'po_receiving_status', 'created_by'])
            ->make(true);
    }

    // public function adjustSupplierForPurchasePaymentDue($supplierId)
    // {
    //     $supplier = Supplier::where('id', $supplierId)->first();

    //     $totalSupplierPurchase = DB::table('purchases')
    //         ->where('supplier_id', $supplierId)
    //         ->select(DB::raw('sum(total_purchase_amount) as total_purchase'))
    //         ->groupBy('supplier_id')->get();

    //     $totalSupplierPayment = DB::table('supplier_payments')
    //         ->where('supplier_id', $supplierId)
    //         ->where('type', 1)
    //         ->select(
    //             DB::raw('sum(paid_amount) as s_paid'),
    //             DB::raw('sum(less_amount) as less')
    //         )->groupBy('supplier_id')->get();

    //     $totalPurchasePayment = DB::table('purchase_payments')
    //         ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
    //         ->where('purchase_payments.supplier_payment_id', NULL)
    //         ->where('purchase_payments.payment_type', 1)
    //         ->where('purchases.supplier_id', $supplierId)
    //         ->select(DB::raw('sum(paid_amount) as p_paid'))
    //         ->groupBy('purchases.supplier_id')->get();

    //     $totalInvoiceReturn = DB::table('purchase_returns')
    //         ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
    //         ->where('purchases.supplier_id', $supplierId)
    //         ->select(DB::raw('sum(total_return_amount) as total_inv_return_amt'))
    //         ->groupBy('purchases.supplier_id')->get();

    //     $totalSupplierReturn = DB::table('purchase_returns')
    //         ->where('purchase_returns.purchase_id', NULL)
    //         ->where('purchase_returns.supplier_id', $supplierId)
    //         ->select(
    //             DB::raw('sum(total_return_amount) as total_sup_return_amt')
    //         )->groupBy('purchase_returns.supplier_id')->get();

    //     $totalInvoiceReturnPayment = DB::table('purchase_payments') // Paid on purchase return invoice due.
    //         ->join('purchases', 'purchase_payments.purchase_id', 'purchases.id')
    //         ->where('purchase_payments.supplier_payment_id', NULL)
    //         ->where('purchase_payments.payment_type', 2)
    //         ->where('purchases.supplier_id', $supplierId)
    //         ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
    //         ->groupBy('purchases.supplier_id')->get();

    //     $totalSupplierReturnPayment = DB::table('supplier_payments') // Paid on Total supplier return due.
    //         ->where('supplier_id', $supplierId)
    //         ->where('type', 2)
    //         ->select(DB::raw('sum(paid_amount) as sr_paid'))
    //         ->groupBy('supplier_id')->get();

    //     $__totalInvoiceReturnPayment = DB::table('purchase_payments') // Paid on supplier return invoice due.
    //         ->where('purchase_payments.purchase_id', NULL)
    //         ->where('purchase_payments.supplier_payment_id', NULL)
    //         ->where('purchase_payments.payment_type', 2)
    //         ->where('purchase_payments.supplier_id', $supplierId)
    //         ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
    //         ->groupBy('purchase_payments.supplier_id')->get();

    //     $totalPurchase = $totalSupplierPurchase->sum('total_purchase');
    //     $totalPaid = $totalSupplierPayment->sum('s_paid') + $totalPurchasePayment->sum('p_paid');
    //     $totalLess = $totalSupplierPayment->sum('less');
    //     $totalReturn = $totalInvoiceReturn->sum('total_inv_return_amt')
    //         + $totalSupplierReturn->sum('total_sup_return_amt');

    //     $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid')
    //         + $totalSupplierReturnPayment->sum('sr_paid')
    //         + $__totalInvoiceReturnPayment->sum('total_inv_return_paid');

    //     $totalDue = ($totalPurchase + $supplier->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn - $totalLess;
    //     $returnDue = $totalReturn - ($totalPurchase + $supplier->opening_balance - $totalPaid) - $totalReturnPaid;

    //     $supplier->total_purchase = $totalPurchase;
    //     $supplier->total_paid = $totalPaid;
    //     $supplier->total_less = $totalLess;
    //     $supplier->total_purchase_due = $totalDue;
    //     $supplier->total_return = $totalReturn;
    //     $supplier->total_purchase_return_due = $returnDue > 0 ? $returnDue : 0;
    //     $supplier->save();
    //     return $totalDue;
    // }

    public function adjustSupplierForPurchasePaymentDue($supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();

        $amounts = DB::table('supplier_ledgers')
            ->where('supplier_ledgers.supplier_id', $supplierId)->select('voucher_type', DB::raw('SUM(amount) as amt'))
            ->groupBy('supplier_ledgers.voucher_type')->get();

        $openingBalance = 0;
        $totalPurchaseAndOrder = 0;
        $totalPaid = 0;
        $totalReturn = 0;
        $totalLess = 0;
        $totalRefund = 0;

        foreach ($amounts as $amount) {

            if ($amount->voucher_type == 0) {

                $openingBalance += $amount->amt;
            } elseif ($amount->voucher_type == 1) {

                $totalPurchaseAndOrder += $amount->amt;
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

        $totalDue = ($totalPurchaseAndOrder + $openingBalance + $totalRefund) - $totalPaid - $totalReturn - $totalLess;

        $totalReturnDue = $totalReturn - ($totalPurchaseAndOrder + $openingBalance - $totalPaid) - $totalRefund;

        $supplier->total_purchase = $totalPurchaseAndOrder;
        $supplier->total_paid = $totalPaid;
        $supplier->total_less = $totalLess;
        $supplier->total_purchase_due = $totalDue;
        $supplier->total_return = $totalReturn;
        $supplier->total_purchase_return_due = $totalReturnDue > 0 ? $totalReturnDue : 0;
        $supplier->save();

        return $totalDue;
    }

    public static function voucherTypes()
    {
        return [
            1 => 'Purchases',
            2 => 'Purchase Returns',
            3 => 'Purchase Payments',
            4 => 'Return Payments',
            5 => 'Supplier Payments',
            6 => 'Received From Supplier',
        ];
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => [
                'name' => 'Opening Balance',
                'id' => 'purchase_id',
                'voucher_no' => 'purchase_inv_id',
                'amt' => 'credit',
                'par' => 'purchase_par'
            ],
            1 => [
                'name' => 'Purchase',
                'id' => 'purchase_id',
                'voucher_no' => 'purchase_inv_id',
                'amt' => 'credit',
                'par' => 'purchase_par',
            ],
            2 => [
                'name' => 'Purchase Return',
                'id' => 'purchase_return_id',
                'voucher_no' => 'return_inv_id',
                'amt' => 'debit',
                'par' => 'purchase_return_par',
            ],
            3 => [
                'name' => 'Payment', // Purchase invoice wise payment
                'id' => 'purchase_payment_id',
                'voucher_no' => 'payment_voucher_no',
                'amt' => 'debit',
                'par' => 'purchase_payment_par',
            ],
            4 => [
                'name' => 'Received Return Amt.', // Purchase/Purchase Return invoice wise Receive Return Amt.
                'id' => 'purchase_payment_id',
                'voucher_no' => 'payment_voucher_no',
                'amt' => 'credit',
                'par' => 'purchase_payment_par',
            ],
            5 => [
                'name' => 'Payment', // Supplier wise payment
                'id' => 'supplier_payment_id',
                'voucher_no' => 'supplier_payment_voucher',
                'amt' => 'debit',
                'par' => 'supplier_payment_par',
            ],
            6 => [
                'name' => 'Received Return Amt.', // Supplier wise Receive Return Amt.
                'id' => 'supplier_payment_id',
                'voucher_no' => 'supplier_payment_voucher',
                'amt' => 'credit',
                'par' => 'supplier_payment_par',
            ],
        ];

        return $data[$voucher_type_id];
    }

    public function addSupplierLedger($voucher_type_id, $supplier_id, $branch_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);
        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->branch_id = $branch_id;
        $addSupplierLedger->supplier_id = $supplier_id;
        $addSupplierLedger->date = $fixed_date ? $fixed_date : date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addSupplierLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addSupplierLedger->{$voucher_type['id']} = $trans_id;
        $addSupplierLedger->{$voucher_type['amt']} = $amount;
        $addSupplierLedger->amount = $amount;
        $addSupplierLedger->amount_type = $voucher_type['amt'];
        $addSupplierLedger->voucher_type = $voucher_type_id;
        $addSupplierLedger->running_balance = 0;
        $addSupplierLedger->save();
    }

    public function updateSupplierLedger($voucher_type_id, $supplier_id, $branch_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);

        $updateSupplierLedger = SupplierLedger::where($voucher_type['id'], $trans_id)
            ->where('branch_id', $branch_id)
            ->where('supplier_id', $supplier_id)
            ->where('voucher_type', $voucher_type_id)
            ->first();

        if ($updateSupplierLedger) {

            //$updateSupplierLedger->supplier_id = $supplier_id;
            $previousTime = date('H:i:s', strtotime($updateSupplierLedger->report_date));
            $updateSupplierLedger->branch_id = $branch_id;
            $updateSupplierLedger->date = $fixed_date ? date('d-m-Y', strtotime($fixed_date)) : $date;
            $updateSupplierLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $updateSupplierLedger->{$voucher_type['amt']} = $amount;
            $updateSupplierLedger->amount = $amount;
            $updateSupplierLedger->save();
        } else {

            $this->addSupplierLedger($voucher_type_id, $supplier_id, $branch_id, $date, $trans_id, $amount, $fixed_date);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceLayout;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceLayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['inv_lay'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $layouts = DB::table('invoice_layouts')->orderBy('id', 'DESC')->select('id', 'name', 'is_default', 'is_header_less','status');
            return DataTables::of($layouts)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name . ' ' . ($row->is_default == 1 ? '<span class="badge bg-primary">Default</span>' : '');
                })
                ->editColumn('is_header_less', function ($row)
                {
                    return $row->is_header_less == 1 ? '<span class="badge bg-info">Yes</span>' :  '<span class="badge bg-secondary">None</span>';
                })
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('invoices.layouts.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown action-btn" title="'.__("Cancel").'" id="change_status" href="' . route('invoices.layouts.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown action-btn" title="'.__("Undo").'" id="change_status" href="' . route('invoices.layouts.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    if ($row->is_default == 0) {
                        // $html .= '<a href="' . route('invoices.layouts.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                        $html .= '<a href="' . route('invoices.layouts.set.default', [$row->id]) . '" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                        Set Default
                        </a>';
                    }
                    $html .= '</div>';
                    return $html;
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


                ->rawColumns(['action', 'name', 'is_header_less','status'])
                ->make(true);
        }
        return view('settings.invoices.layouts.index');
    }

    public function create()
    {
        return view('settings.invoices.layouts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:invoice_layouts,name',
            'invoice_heading' => 'required',
            'quotation_heading' => 'required',
            'draft_heading' => 'required',
            'challan_heading' => 'required',
        ]);

        if (isset($request->is_header_less)) {
            $this->validate($request, [
                'gap_from_top' => 'required',
            ]);
        }

        $addLayout = new InvoiceLayout();
        $addLayout->name = $request->name;
        $addLayout->layout_design = $request->design;
        $addLayout->show_shop_logo = isset($request->show_shop_logo) ? 1 : 0;
        $addLayout->show_seller_info = isset($request->show_seller_info) ? 1 : 0;
        $addLayout->show_total_in_word = isset($request->show_total_in_word) ? 1 : 0;
        $addLayout->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $addLayout->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : NULL;
        $addLayout->header_text = $request->header_text;
        $addLayout->sub_heading_1 = $request->sub_heading_1;
        $addLayout->sub_heading_2 = $request->sub_heading_2;
        $addLayout->sub_heading_3 = $request->sub_heading_3;
        $addLayout->invoice_heading = $request->invoice_heading;
        $addLayout->quotation_heading = $request->quotation_heading;
        $addLayout->draft_heading = $request->draft_heading;
        $addLayout->challan_heading = $request->challan_heading;
        $addLayout->branch_landmark = isset($request->branch_landmark) ? 1 : 0;
        $addLayout->branch_city = isset($request->branch_city) ? 1 : 0;
        $addLayout->branch_state = isset($request->branch_state) ? 1 : 0;
        $addLayout->branch_zipcode = isset($request->branch_zipcode) ? 1 : 0;
        $addLayout->branch_phone = isset($request->branch_phone) ? 1 : 0;
        $addLayout->branch_alternate_number = isset($request->branch_alternate_number) ? 1 : 0;
        $addLayout->branch_email = isset($request->branch_email) ? 1 : 0;
        $addLayout->product_img = isset($request->product_img) ? 1 : 0;
        $addLayout->product_cate = isset($request->product_cate) ? 1 : 0;
        $addLayout->product_brand = isset($request->product_brand) ? 1 : 0;
        $addLayout->product_imei = isset($request->product_imei) ? 1 : 0;
        $addLayout->product_w_type = isset($request->product_w_type) ? 1 : 0;
        $addLayout->product_w_duration = isset($request->product_w_duration) ? 1 : 0;
        $addLayout->product_w_discription = isset($request->product_w_discription) ? 1 : 0;
        $addLayout->product_discount = isset($request->product_discount) ? 1 : 0;
        $addLayout->product_tax = isset($request->product_tax) ? 1 : 0;
        $addLayout->product_price_inc_tax = isset($request->product_price_inc_tax) ? 1 : 0;
        $addLayout->product_price_exc_tax = isset($request->product_price_exc_tax) ? 1 : 0;
        $addLayout->customer_name = isset($request->customer_name) ? 1 : 0;
        $addLayout->customer_address = isset($request->customer_address) ? 1 : 0;
        $addLayout->customer_tax_no = isset($request->customer_tax_no) ? 1 : 0;
        $addLayout->customer_phone = isset($request->customer_phone) ? 1 : 0;
        $addLayout->bank_name = $request->bank_name;
        $addLayout->bank_branch = $request->bank_branch;
        $addLayout->account_name = $request->account_name;
        $addLayout->account_no = $request->account_no;
        $addLayout->invoice_notice = $request->invoice_notice;
        $addLayout->footer_text = $request->footer_text;
        $addLayout->save();

        $invoiceLayouts = InvoiceLayout::all();
        if (count($invoiceLayouts) == 1) {
            $defaultLayouts = InvoiceLayout::first();
            $defaultLayouts->is_default = 1;
            $defaultLayouts->save();
        }


        return response()->json(__('Successfully invoice layout is created'));

    }

    public function edit($layoutId)
    {
        $layout = DB::table('invoice_layouts')->where('id', $layoutId)->first();
        return view('settings.invoices.layouts.edit', compact('layout'));
    }

    public function update(Request $request, $layoutId)
    {
        $this->validate($request, [
            'name' => 'required|unique:invoice_layouts,name,'.$layoutId,
        ]);

        if (isset($request->is_header_less)) {
            $this->validate($request, [
                'gap_from_top' => 'required',
            ]);
        }

        $updateLayout = InvoiceLayout::where('id', $layoutId)->first();
        $updateLayout->name = $request->name;
        $updateLayout->layout_design = $request->design;
        $updateLayout->show_shop_logo = isset($request->show_shop_logo) ? 1 : 0;
        $updateLayout->show_seller_info = isset($request->show_seller_info) ? 1 : 0;
        $updateLayout->show_total_in_word = isset($request->show_total_in_word) ? 1 : 0;
        $updateLayout->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $updateLayout->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : NULL;
        $updateLayout->header_text = $request->header_text;
        $updateLayout->sub_heading_1 = $request->sub_heading_1;
        $updateLayout->sub_heading_2 = $request->sub_heading_2;
        $updateLayout->sub_heading_3 = $request->sub_heading_3;
        $updateLayout->invoice_heading = $request->invoice_heading;
        $updateLayout->quotation_heading = $request->quotation_heading;
        $updateLayout->draft_heading = $request->draft_heading;
        $updateLayout->challan_heading = $request->challan_heading;
        $updateLayout->branch_landmark = isset($request->branch_landmark) ? 1 : 0;
        $updateLayout->branch_city = isset($request->branch_city) ? 1 : 0;
        $updateLayout->branch_state = isset($request->branch_state) ? 1 : 0;
        $updateLayout->branch_zipcode = isset($request->branch_zipcode) ? 1 : 0;
        $updateLayout->branch_phone = isset($request->branch_phone) ? 1 : 0;
        $updateLayout->branch_alternate_number = isset($request->branch_alternate_number) ? 1 : 0;
        $updateLayout->branch_email = isset($request->branch_email) ? 1 : 0;
        $updateLayout->product_img = isset($request->product_img) ? 1 : 0;
        $updateLayout->product_cate = isset($request->product_cate) ? 1 : 0;
        $updateLayout->product_brand = isset($request->product_brand) ? 1 : 0;
        $updateLayout->product_imei = isset($request->product_imei) ? 1 : 0;
        $updateLayout->product_w_type = isset($request->product_w_type) ? 1 : 0;
        $updateLayout->product_w_duration = isset($request->product_w_duration) ? 1 : 0;
        $updateLayout->product_w_discription = isset($request->product_w_discription) ? 1 : 0;
        $updateLayout->product_discount = isset($request->product_discount) ? 1 : 0;
        $updateLayout->product_tax = isset($request->product_tax) ? 1 : 0;
        $updateLayout->product_price_inc_tax = isset($request->product_price_inc_tax) ? 1 : 0;
        $updateLayout->product_price_exc_tax = isset($request->product_price_exc_tax) ? 1 : 0;
        $updateLayout->customer_name = isset($request->customer_name) ? 1 : 0;
        $updateLayout->customer_address = isset($request->customer_address) ? 1 : 0;
        $updateLayout->customer_tax_no = isset($request->customer_tax_no) ? 1 : 0;
        $updateLayout->customer_phone = isset($request->customer_phone) ? 1 : 0;
        $updateLayout->bank_name = $request->bank_name;
        $updateLayout->bank_branch = $request->bank_branch;
        $updateLayout->account_name = $request->account_name;
        $updateLayout->account_no = $request->account_no;
        $updateLayout->invoice_notice = $request->invoice_notice;
        $updateLayout->footer_text = $request->footer_text;
        $updateLayout->save();

        return response()->json(__('Successfully invoice layout is updated'));
    }

    public function changeStatus($schemaId)
    {
        $statusChange = InvoiceLayout::where('id', $schemaId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Invoice Layout is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Invoice Layout is activated Successfully'));

        }
    }

    public function delete(Request $request, $schemaId)
    {
        $deleteInvoice = InvoiceLayout::find($schemaId);
        if (!is_null($deleteInvoice)) {
            $deleteInvoice->delete();
        }

        return response()->json(__('Successfully invoice layout is deleted'));
    }

    public function setDefault($schemaId)
    {
        $defaultLayout = InvoiceLayout::where('is_default', 1)->first();
        if ($defaultLayout) {
            $defaultLayout->is_default = 0;
            $defaultLayout->save();
        }

        $updateLayout = InvoiceLayout::where('id', $schemaId)->first();
        $updateLayout->is_default = 1;
        $updateLayout->save();
        return response()->json('Default set successfully');
    }

}

<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $methods = DB::table('payment_methods')
            ->orderBy('id', 'DESC');

            return DataTables::of($methods)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="' . route('settings.payment.method.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                        // $html .= '<a href="' . route('settings.payment.method.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                        if ($row->status == 1) {
                            $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('settings.payment.method.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                        } else {
                            $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('settings.payment.method.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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
                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('settings.payment_method.index');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name',
            ],
        );

        PaymentMethod::insert([
            'name' => $request->name,
        ]);


            return response()->json(__('Payment method created successfully.'));


    }

    public function edit($id)
    {
        $method = DB::table('payment_methods')->where('id', $id)->first();

        return view('settings.payment_method.ajax_view.edit_payment_method', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name,' . $id,
            ],

        );

        $updatePayment = PaymentMethod::where('id', $id)->first();

        $updatePayment->update([
            'name' => $request->name,
        ]);

        return response()->json(__('Payment method updated successfully.'));
    }

    public function changeStatus($id)
    {
        $statusChange = PaymentMethod::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Payment Method is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Payment Method is activated Successfully'));

        }
    }

    public function delete(Request $request, $id)
    {
        $deletePaymentMethod = PaymentMethod::where('id', $id)->first();

        if (!is_null($deletePaymentMethod)) {

            if ($deletePaymentMethod->is_fixed == 1) {

                return response()->json('Can not delete, This payment method is fixed');
            }

            $deletePaymentMethod->delete();
        }

        return response()->json(__('Payment method deleted successfully.'));
    }
}

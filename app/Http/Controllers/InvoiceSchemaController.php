<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceSchemaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Category main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['inv_sc'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $schemas = DB::table('invoice_schemas')->orderBy('id', 'DESC');
            return DataTables::of($schemas)
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('invoices.schemas.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown action-btn" title="'.__("Cancel").'" id="change_status" href="' . route('invoices.schemas.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown action-btn" title="'.__("Undo").'" id="change_status" href="' . route('invoices.schemas.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    if ($row->is_default == 0) {
                        // $html .= '<a href="' . route('invoices.schemas.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                        $html .= '<a href="' . route('invoices.schemas.set.default', [$row->id]) . '" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                        Set Default
                        </a>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('prefix', function ($row) {
                    return $row->format == 2 ? date('Y') : $row->prefix;
                })
                ->editColumn('name', function ($row) {
                    return $row->name . ' ' . ($row->is_default == 1 ? '<span class="badge bg-primary">Default</span>' : '');
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
                ->rawColumns(['action', 'prefix', 'name','status'])
                ->make(true);
        }

        return view('settings.invoices.schemas.index');
    }

    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:invoice_schemas,name',
            'prefix' => 'required',
        ]);

        $addSchema = new InvoiceSchema();
        $addSchema->name = $request->name;
        $addSchema->format = $request->format;
        $addSchema->prefix = $request->prefix;
        $addSchema->start_from = $request->start_from;

        if (isset($request->set_as_default)) {
            $defaultSchema = InvoiceSchema::where('is_default', 1)->first();
            if ($defaultSchema) {
                $defaultSchema->is_default = 0;
                $defaultSchema->save();
            }
            $addSchema->is_default = 1;
        }
        $addSchema->save();

        $invoiceSchemas = InvoiceSchema::all();
        if (count($invoiceSchemas) == 1) {
            $defaultSchema = InvoiceSchema::first();
            $defaultSchema->is_default = 1;
            $defaultSchema->save();
        }

        return response()->json(__('Successfully invoice schema is added'));

    }

    public function edit($schemaId)
    {
        $schema = DB::table('invoice_schemas')->where('id', $schemaId)->first();
        return view('settings.invoices.schemas.ajax_view.edit_modal', compact('schema'));
    }

    public function update(Request $request, $schemaId)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:invoice_schemas,name,' . $schemaId,
            'prefix' => 'required',
        ]);

        $updateSchema = InvoiceSchema::where('id', $schemaId)->first();
        $updateSchema->name = $request->name;
        $updateSchema->format = $request->format;
        $updateSchema->prefix = $request->prefix;
        $updateSchema->start_from = $request->start_from;
        $updateSchema->save();
        return response()->json(__('Successfully invoice schema is updated'));
    }

     public function changeStatus($schemaId)
    {
        $statusChange = InvoiceSchema::where('id', $schemaId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Invoice Schema is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Invoice Schema is activated Successfully'));

        }
    }

    public function delete(Request $request, $schemaId)
    {
        $deleteSchema = InvoiceSchema::find($schemaId);
        if (!is_null($deleteSchema)) {
            $deleteSchema->delete();
        }

        return response()->json(__('Successfully invoice schema is deleted'));
    }

    public function setDefault($schemaId)
    {
        $defaultSchema = InvoiceSchema::where('is_default', 1)->first();
        if ($defaultSchema) {
            $defaultSchema->is_default = 0;
            $defaultSchema->save();
        }

        $updateSchema = InvoiceSchema::where('id', $schemaId)->first();
        $updateSchema->is_default = 1;
        $updateSchema->save();
        return response()->json('Default set successfully');
    }
}

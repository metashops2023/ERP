<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Category main page/index page
    public function index()
    {
        return view('expenses.categories.index');
    }

    // Get all category by ajax
    public function allCategory()
    {
        $categories = ExpenseCategory::orderBy('id', 'DESC')->get();
        return view('expenses.categories.ajax_view.category_list', compact('categories'));
    }

    // Store expense category
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $lastExpenseCategory = DB::table('expense_categories')->orderBy('id', 'desc')->first();
        $code = 0;
        if ($lastExpenseCategory) {
            $code = ++$lastExpenseCategory->id;
        }else {
            $code = 1;
        }

        ExpenseCategory::insert([
            'name' => $request->name,
            'code' => $request->code ? $request->code : $code,
        ]);


            return response()->json(__('Expense category created successfully'));


    }

    // Update expense category
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateCategory = ExpenseCategory::where('id', $request->id)->first();

        $updateCategory->update([
            'name' => $request->name,
        ]);

        return response()->json(__('Expense category updated successfully'));
    }

    public function delete(Request $request, $categoryId)
    {
        //return $categoryId;
        $deleteCategory = ExpenseCategory::find($categoryId);

        if (!is_null($deleteCategory)) {

            $deleteCategory->delete();
        }
        return response()->json(__('Expense category deleted successfully'));
    }
}

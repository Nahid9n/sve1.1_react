<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $data = ExpenseCategory::paginate(20);

        return view('backEnd.admin.expense-categories.index', compact('data'));
    }

    public function store(Request $request)
    {
        ExpenseCategory::create($request->all());

        return back()->with('success', 'Expense category created successfully.');
    }

    public function update(Request $request)
    {
        ExpenseCategory::find($request->id)->update($request->all());

        return back()->with('success', 'Expense category updated successfully.');
    }

    public function status($id, $status)
    {
        $supplier = ExpenseCategory::FindOrFail($id);
        $supplier->status = $status;
        $supplier->update();

        return back()->with('success', 'Expense category Status Updated Successfully');
    }

    public function delete($id)
    {
        ExpenseCategory::findOrFail($id)->delete();

        return back()->with('success', 'Expense category deleted successfully.');
    }
}

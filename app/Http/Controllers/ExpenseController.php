<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use App\Expense;
use App\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $data = Expense::with('get_transaction')->paginate(20);
        // dd($data);
        $categories = ExpenseCategory::where('status', 1)->pluck('name', 'id');
        $accounts = Account::where('status', 1)->get();

        return view('backEnd.admin.expense.index', compact('data', 'categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $expense = Expense::create($request->all());
        Account::findOrFail($request->account_id)->decrement('balance', $request->amount);
        AccountTransaction::create([
            'expense_id' => $expense->id,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'transaction_type' => 1,
            'purpose' => $request->purpose,
        ]);

        return back()->with('success', 'Expense created successfully.');
    }

    public function update(Request $request)
    {
        $expense = Expense::find($request->id);
        $amount = $expense->amount;
        $expense->update($request->all());

        $account = Account::findOrFail($request->account_id);
        $account->increment('balance', $amount);
        $account->decrement('balance', $request->amount);

        $expense->get_transaction()->delete();

        AccountTransaction::create([
            'expense_id' => $request->id,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'transaction_type' => 1,
            'purpose' => $request->purpose,
        ]);

        return back()->with('success', 'Expense updated successfully.');
    }

    public function status($id, $status)
    {
        $supplier = Expense::FindOrFail($id);
        $supplier->status = $status;
        $supplier->update();

        return back()->with('success', 'Expense Status Updated Successfully');
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->get_transaction->get_account()->increment('balance', $expense->amount);
        $expense->get_transaction()->delete();
        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }
}

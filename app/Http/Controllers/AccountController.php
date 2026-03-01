<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $data = Account::latest()->paginate(20);
        $accounts = Account::where('status', 1)->get();

        return view('backEnd.admin.account.index', compact('data', 'accounts'));
    }

    public function store(Request $request)
    {
        Account::where('is_default', 1)->update(['is_default' => 0]);
        $input = $request->all() + ['is_default' => 1];
        $account = Account::create($input);
        $data = [
            'account_id' => $account->id,
            'purpose' => 'New account created.',
            'amount' => $request->balance,
            'transaction_type' => 0,
        ];
        AccountTransaction::create($data);

        return redirect()->route('admin.account.index')->with('success', 'Account added successfully.');
    }

    public function update(Request $request)
    {
        Account::findOrFail($request->id)->update($request->all());

        return redirect()->route('admin.account.index')->with('success', 'Account update successfully.');
    }

    public function addBalance(Request $request)
    {
        $account = Account::find($request->account_id);
        $account->update(['balance' => $account->balance + $request->balance]);
        if ($account->account_type == 1) {
            $purpose = 'Balance added into Acc. No. '.$account->bank_account_no.', '.$account->bank_name.', '.$account->branch_name;
        } elseif ($account->account_type == 2) {
            $purpose = 'Balance added into Bkash Number '.$account->bkash_no;
        } elseif ($account->account_type == 3) {
            $purpose = 'Balance added into  Nagad Number '.$account->nagad_no;
        } else {
            $purpose = 'Balance added into  Rocket Number '.$account->rocket_no;
        }

        $data = [
            'account_id' => $account->id,
            'purpose' => $purpose,
            'amount' => $request->balance,
            'transaction_type' => 0,
        ];

        AccountTransaction::create($data);

        return back()->with('success', 'Balance added successfully.');
    }

    public function status($id, $status)
    {
        $data = Account::findOrFail($id);
        $data->status = $status;
        $data->update();

        return back()->with('success', 'Status update successfully.');
    }

    public function setDefaultAccount($id, $status)
    {
        $data = Account::count();

        if ($data < 2 && $status == 1) {
            return back()->with('error', 'Can not set default Default.');
        }

        $account = Account::findOrFail($id);
        $account->is_default = $status;
        $account->update();

        Account::where('id', '!=', $id)->update(['is_default' => 0]);

        return back()->with('success', 'Default Account Set Successfully.');
    }

    public function delete($id)
    {
        $data = Account::findOrFail($id);
        if ($data->is_default == 1) {
            return back()->with('error', 'Can not delete default language.');
        }
        $data->delete();

        return back()->with('success', 'Account delete successfully.');
    }
}

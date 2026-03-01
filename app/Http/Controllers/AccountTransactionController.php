<?php

namespace App\Http\Controllers;

use App\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountTransactionController extends Controller
{
    // index
    public function index(Request $request)
    {
        $data['transactions'] = AccountTransaction::query();
        $daterange = null;
        if ($request->start_date && $request->end_date) {
            $daterange = date('d/m/Y', strtotime($request->start_date)).' - '.date('d/m/Y', strtotime($request->end_date));

            $start = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');

            $data['transactions']->whereBetween('created_at', [$start, $end]);

        }
        if ($request->transaction_type != null) {
            $data['transactions']->where('transaction_type', $request->transaction_type);
        }
        $data['transactions'] = $data['transactions']->latest()->paginate(20);

        return view('backEnd.admin.transaction.index', compact('data', 'daterange'));
    }
}

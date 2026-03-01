<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccountTransactionExport implements FromView, ShouldAutoSize
{
    public $cashIn;

    public $cashOut;

    public function __construct($cash_in, $cash_out)
    {
        $this->cashIn = $cash_in;
        $this->cashOut = $cash_out;
    }

    public function view(): View
    {

        return view('backEnd.admin.report.export.account-trans', [
            'cash_in' => $this->cashIn,
            'cash_out' => $this->cashOut,
        ]);
    }
}

<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class ProfitLossReportExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         //
//     }
// }
class ProfitLossReportExport implements FromView, ShouldAutoSize
{
    public $totalSell;

    public $totalPurchaseCost;

    public $totalExpenseCost;

    public function __construct($totalSell, $totalPurchaseCost, $totalExpenseCost)
    {
        $this->$totalSell = $totalSell;
        $this->totalPurchaseCost = $totalPurchaseCost;
        $this->totalExpenseCost = $totalExpenseCost;
    }

    public function view(): View
    {

        return view('backEnd.admin.report.export.profit_loss', [
            'totalSell' => $this->totalSell,
            'totalPurchaseCost' => $this->totalPurchaseCost,
            'totalExpenseCost' => $this->totalExpenseCost,
        ]);
    }
}

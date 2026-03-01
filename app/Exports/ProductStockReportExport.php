<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class ProductStockReportExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         //
//     }
// }
class ProductStockReportExport implements FromView, ShouldAutoSize
{
    public $productRange;

    public function __construct($productRange)
    {
        $this->productRange = $productRange;
    }

    public function view(): View
    {

        return view('backEnd.admin.report.export.product-stock', [
            'productRange' => $this->productRange,
        ]);
    }
}

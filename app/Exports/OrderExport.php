<?php

namespace App\Exports;

use App\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromView, ShouldAutoSize
{
    public $data;

    public $status;

    public function __construct($data, $status)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function view(): View
    {
        $data = Order::find($this->data);

        if ($this->status == 1) {
            $view = 'backEnd.admin.orders.courier_csv.stead_fast_csv';
        } else {
            $view = 'backEnd.admin.orders.courier_csv.redex_csv';
        }

        return view($view, [
            'data' => $data,
        ]);
    }
}

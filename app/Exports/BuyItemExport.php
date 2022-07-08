<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class BuyItemExport implements FromView
{
    use Exportable;

    protected $data;
    protected $date;

    public function __construct($data, $date)
    {
        $this->data = $data;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('item.report-item', [
            'date' => $this->date,
            'data' => $this->data,
        ]);
    }
}

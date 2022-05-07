<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class StockReportExcel implements WithColumnWidths, FromView, WithStyles, WithEvents
{
    use Exportable;

    protected $item_stocks;
    protected $date;
    protected $index;

    public function __construct($item_stocks, $date)
    {
        $this->item_stocks = $item_stocks;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('stock.report-stock', [
            'date' => $this->date,
            'item_stocks' => $this->item_stocks,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        if (array_key_exists('Barang', $this->item_stocks->toArray())) {
            $this->index = count($this->item_stocks['Barang']) + 8;
        }
        return [
            // Style the first row as bold text.
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            $this->index => ['font' => ['bold' => true]],
            $this->index + 1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 20,
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        if (array_key_exists('Barang', $this->item_stocks->toArray())) {
            $this->index = count($this->item_stocks['Barang']) + 8;
        }
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A4:F4')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A5:F5')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . $this->index . ':F' . $this->index)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($this->index + 1) . ':F' . ($this->index + 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

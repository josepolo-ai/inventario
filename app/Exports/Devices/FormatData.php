<?php

namespace App\Exports\Devices;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class FormatData implements FromView, WithTitle, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.devices', [
            'data' => $this->data
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Obtener el rango de datos (incluyendo el encabezado)
                $worksheet = $event->sheet->getDelegate();
                $worksheet->getStyle('A1:K2')->getFont()->setBold(true); // Negrita en los encabezados

                // Autoajustar todas las columnas de A a K
                foreach (range('A', 'K') as $column) {
                    $worksheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Alineación horizontal y vertical en las columnas seleccionadas
                foreach (range('A', 'K') as $column) {
                    $worksheet->getStyle($column . '1:' . $column . $worksheet->getHighestRow())
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Equipos';
    }
}

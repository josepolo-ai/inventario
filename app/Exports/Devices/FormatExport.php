<?php

namespace App\Exports\Devices;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FormatExport implements WithMultipleSheets
{

	use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new FormatData($this->data);

        return $sheets;
    }
}

<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BlackListExport implements FromArray,WithHeadings
{
    protected $clients;

    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    public function array(): array
    {
        return $this->clients;
    }

    public function headings():array{
        return[
            'No.',
            'Name',
            'National ID',
            'Mobile No',
            'Reason'
        ];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ClientExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
           
        ];
    }

    public function headings(): array
    {
        return ['Name', 'Mobile No','Address', 'Client Manager Name', 'Client Manager Email', 'Client Manager Mobile No'];
    }
}

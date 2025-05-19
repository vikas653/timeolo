<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientListExport implements FromCollection, WithHeadings, WithMapping
{

    // protected $timesheet_id;

    public function __construct()
    {
       
        // $this->timesheet_id = $timesheet_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role_id', 2)->get();
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        // Define column headings
        return [
            'Name',
             'Mobile No','Address','Client Manager Name','Client Manager Email','Client Manager Mobile No'


        ];
    }
    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Map each row of data
        return [
            $row->name,
            $row->mobile_no,
            $row->address,
            $row->approver_name,
            $row->approver_email,
            $row->approver_phone,

           


        ];
    }
}

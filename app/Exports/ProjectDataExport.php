<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectDataExport implements FromCollection, WithHeadings, WithMapping
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
        // return Project::get();
        return Project::with('term')->get();
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        // Define column headings
        return ['Code', 'Name', 'Start Date (MM/DD/YYYY)', 'End Date (MM/DD/YYYY)', 'Bill Rate', 'Bill By',
        // 'Pay Rate Unit' ,
         'Bill Rate Unit','Approver Name' , 'Approver Email' , 'Approver Phone', 'Term'];
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
            $row->code,
            $row->name,
            $row->start,
            $row->end,
            $row->bill_rate,
            $row->bill_by,
            // $row->pay_rate_currency,
            $row->bill_rate_unit,
            $row->approver_name,
            $row->approver_email,
            $row->approver_phone,
            optional($row->term)->name,
          
            // optional($row->system)->company_name,

            // optional($row->employment)->name, // Safeguard for null values
            // optional($row->system)->name,

        ];
    }
}

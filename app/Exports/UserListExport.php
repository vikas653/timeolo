<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserListExport implements FromCollection, WithHeadings, WithMapping
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
        return User::with(['employment', 'system', ])->where('role_id', 3)->get();
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        // Define column headings
        return [
            'Name',
             'Email','Address','Mobile No','Pay Rate','Currency','Job Title','Date of Birth','SSN','Type of Employment','Type of System Access','Start Date','End Date','Bank Name','Bank Branch Address','Account Holder Name','Bank Account Number','Swift/BIC Code','IBAN Number','Bank Routing Number', 'Pay Rate Unit' ,'Work Email','Location' 


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
            $row->email,
            $row->address,
            $row->moblie_no,
            $row->pay_rate,
            $row->currency,
            $row->job_title,
            $row->dob,
            $row->ssn,
            optional($row->employment)->name, // Safeguard for null values
            optional($row->system)->name,
            $row->start_date,
            $row->end_date,
            $row->bank_name,
            $row->bank_branch_address,
            $row->account_holder_name,
            $row->bank_account_number,
            $row->swift_bic_code,
            $row->iban_number,
            $row->bank_routing_number,
            $row->pay_rate_currency,
            $row->work_email,
            $row->location,
            // optional($row->system)->company_name,



        ];
    }
}

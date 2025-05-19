<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BillExport implements FromCollection, WithHeadings
{
    protected $user, $client, $month, $year;

    public function __construct($user, $client, $month, $year)
    {
        $this->user = $user;
        $this->client = $client;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        
        if (!is_numeric($this->year) || empty($this->month) || !is_string($this->month)) {
            return new Collection();
        }

        try {
            $monthNumber = Carbon::parse("1 {$this->month}")->month;
            $firstDay = Carbon::createFromDate($this->year, $monthNumber, 1)->format('Y/m/d');
            $lastDay = Carbon::createFromDate($this->year, $monthNumber)->endOfMonth()->format('Y/m/d');
        } catch (\Exception $e) {
            return new Collection();
        }

        $query = DB::table('timesheets')
            ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
            ->join('users as consultant', 'timesheet_reports.user_id', '=', 'consultant.id')
            ->join('users as client', 'timesheet_reports.client_id', '=', 'client.id')
            ->join('project', 'timesheet_reports.code', '=', 'project.id')
            ->join('terms', 'project.terms_id', '=', 'terms.id')
            ->leftJoin('vendors', 'consultant.id', '=', 'vendors.user_id') 
            ->leftJoin('project_asign', function ($join) {
                $join->on('project.id', '=', 'project_asign.project_id')
                     ->on('timesheet_reports.user_id', '=', 'project_asign.user_id');
            }) 
            ->select(
                DB::raw("'' as bill_no"),
                'consultant.name as user_name',
                DB::raw("COALESCE(vendors.company_name, '') as vendor_name"),
                DB::raw("COALESCE(consultant.address, '') as mailing_address"),
                'terms.name as terms_name',
                DB::raw("'$firstDay' as bill_date"),
                DB::raw("'$lastDay' as due_date"),
                DB::raw("COALESCE(consultant.location, '') as user_location"),
                DB::raw("'' as memo"),
                DB::raw("'' as category_account"),
                'project.code as project_code',
                'project.name as project_name',
                DB::raw('SUM(timesheet_reports.regular_hours) as quantity'),
                'consultant.pay_rate as user_rate',
                DB::raw('SUM(timesheet_reports.regular_hours * consultant.pay_rate) as total_amount'),
                DB::raw("'' as billable"),
                'client.name as client_name'
            );

        if ($this->user != '0') {
            $query->where('timesheet_reports.user_id', '=', $this->user);
        }

        if ($this->client != '0') {
            $query->where('timesheet_reports.client_id', '=', $this->client);
        }

        if ($this->month != '0') {
            $query->where('timesheets.month', '=', $this->month);
        }

        if ($this->year != '0') {
            $query->where('timesheets.year', '=', $this->year);
        }

        $query->groupBy(
            'consultant.name',
            'vendors.company_name',
            'consultant.address',
            'terms.name',
            'consultant.location',
            'project.code',
            'project.name',
            'consultant.pay_rate',
            'client.name'
        );

        $timesheets = $query->get();

        return $timesheets->isNotEmpty() ? $timesheets : new Collection();
    }

    public function headings(): array
    {
        return [
            'Bill No',
            'User',
            'Vendor',
            'Mailing Address',
            'Terms',
            'Bill Date',
            'Due Date',
            'Location',
            'Memo',
            'Category Account',
            'Project Code',
            'Project Name',
            'Quantity',
            'Pay Rate',
            'Total Amount',
            'Billable',
            'Client',
        ];
    }
}


// namespace App\Exports;

// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Illuminate\Support\Collection;

// class BillExport implements FromCollection, WithHeadings
// {
//     protected $user, $client, $month, $year;

//     public function __construct($user, $client, $month, $year)
//     {
//         $this->user = $user;
//         $this->client = $client;
//         $this->month = $month;
//         $this->year = $year;
//     }

//     public function collection()
//     {
//         // Validate month and year
//         if (!is_numeric($this->year) || empty($this->month) || !is_string($this->month)) {
//             return new Collection();
//         }

//         try {
//             $monthNumber = Carbon::parse("1 {$this->month}")->month;
//             $firstDay = Carbon::createFromDate($this->year, $monthNumber, 1)->format('Y/m/d');
//             $lastDay = Carbon::createFromDate($this->year, $monthNumber)->endOfMonth()->format('Y/m/d');
//         } catch (\Exception $e) {
//             return new Collection();
//         }

//         $query = DB::table('timesheets')
//             ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
//             ->join('users as consultant', 'timesheet_reports.user_id', '=', 'consultant.id')
//             ->join('users as client', 'timesheet_reports.client_id', '=', 'client.id')
//             ->join('project', 'timesheet_reports.code', '=', 'project.id')
//             ->join('terms', 'project.terms_id', '=', 'terms.id')
//             ->join('vendors', 'consultant.id', '=', 'vendors.user_id')
//             ->leftJoin('project_asign', function ($join) {
//                 $join->on('project.id', '=', 'project_asign.project_id')
//                      ->on('timesheet_reports.user_id', '=', 'project_asign.user_id');
//             }) 
//             ->select(
//                 DB::raw("'' as bill_no"),
//                 'consultant.name as user_name',
//                 'vendors.company_name as vendor_name',
//                 'consultant.address as mailing_address',
//                 'terms.name as terms_name',
//                 DB::raw("'$firstDay' as bill_date"),
//                 DB::raw("'$lastDay' as due_date"),
//                 'consultant.location as user_location',
//                 DB::raw("'' as memo"),
//                 DB::raw("'' as category_account"),
//                 'project.code as project_code',
//                 'project.name as project_name',
//                 DB::raw('SUM(timesheet_reports.regular_hours) as quantity'),
//                 'project.bill_rate as pay_rate',
//                 DB::raw('SUM(timesheet_reports.regular_hours * project.bill_rate) as total_amount'),
//                 DB::raw("'' as billable"),
//                 'client.name as client_name'
//             );

//         if ($this->user != '0') {
//             $query->where('timesheet_reports.user_id', '=', $this->user);
//         }

//         if ($this->client != '0') {
//             $query->where('timesheet_reports.client_id', '=', $this->client);
//         }

//         if ($this->month != '0') {
//             $query->where('timesheets.month', '=', $this->month);
//         }

//         if ($this->year != '0') {
//             $query->where('timesheets.year', '=', $this->year);
//         }

//         $query->groupBY(
//            'consultant.name',
//             'vendors.company_name',
//             'consultant.address',
//             'terms.name',
//             'consultant.location',
//             'project.code',
//             'project.name',
//             'project.pay_rate_currency',
//             'client.name'
//         );
//         $timesheets = $query->get();

//         return $timesheets->isNotEmpty() ? $timesheets : new Collection();
//     }

//     public function headings(): array
//     {
//         return [
//             'Bill No',
//             'User',
//             'Vendor',
//             'Mailing Address',
//             'Terms',
//             'Bill Date',
//             'Due Date',
//             'Location',
//             'Memo',
//             'Category Account',
//             'Project Code',
//             'Project Name',
//             'Quantity',
//             'Pay Rate',
//             'Total Amount',
//             'Billable',
//             'Client',
//         ];
//     }
// } 

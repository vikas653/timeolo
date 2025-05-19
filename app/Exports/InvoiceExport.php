<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class InvoiceExport implements FromCollection, WithHeadings
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
        // Validate month and year
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
            ->leftJoin('project_asign', function ($join) {
                $join->on('project.id', '=', 'project_asign.project_id')
                     ->on('timesheet_reports.user_id', '=', 'project_asign.user_id');
            }) 
            ->select(
                DB::raw("'' as invoice_no"),
                'consultant.name as consultant_name',
                'client.name as customer',
                DB::raw("'$firstDay' as invoice_date"),
                DB::raw("'$lastDay' as due_date"),
                'terms.name as terms',
                DB::raw("'' as memo"),
                'project.code as item_code',
                'project.name as item_description',
                DB::raw('SUM(timesheet_reports.regular_hours) as item_quantity'),
                'project.bill_rate as item_rate',
                DB::raw('SUM(timesheet_reports.regular_hours * project.bill_rate) as item_amount'),
                'project.bill_rate_unit as currency',
                DB::raw("'$firstDay' as service_date")
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

        $query->groupBY(
            'consultant.name',
            'client.name',
            'project.code',
            'project.name',
            'project.bill_rate',
            'project.bill_rate_unit'
        );
        $timesheets = $query->get();

        return $timesheets->isNotEmpty() ? $timesheets : new Collection();
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Consultant Name',
            'Customer',
            'Invoice Date',
            'Due Date',
            'Terms',
            'Memo',
            'Item (Product/Service)',
            'Item Description',
            'Item Quantity',
            'Item Rate',
            'Item Amount',
            'Currency',
            'Service Date',
        ];
    }
}
                // DB::raw('timesheet_reports.regular_hours * project.bill_rate as item_amount'),

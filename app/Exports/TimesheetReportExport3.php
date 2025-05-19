<?php

namespace App\Exports;

use App\Models\TimesheetReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetReportExport3 implements FromCollection, WithHeadings, WithMapping
{

    protected $timesheet_id;

    public function __construct($timesheet_id)
    {
       
        $this->timesheet_id = $timesheet_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TimesheetReport::select('date','client_id','user_id','activity', 'regular_hours','date','billable','code')
            ->where('timesheet_id', $this->timesheet_id)
            ->whereHas('client')->whereHas('user')
            ->get();
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        // Define column headings
        return [
            'User',
            'Client',
            'Date',
            'Activity',
            'Hours',
            'Approver Name',
            'Approver Email',
            'Billable (0/1)',
            'Project Code'


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
            $row->user->name,
            $row->client->name,
            $row->date,
            $row->activity,
            $row->regular_hours,
            $row->approver_name,
            $row->approver_email,
            $row->billable,
            $row->project->code ??'',
            $row->date,

        ];
    }
}

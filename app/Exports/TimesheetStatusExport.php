<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetStatusExport implements FromCollection, WithHeadings
{
    protected  $month, $year,$status;
    public function __construct( $month, $year,$status)
    {
        $this->month = $month;
        $this->year = $year;
        $this->status = $status;

    }

    public function collection()
    {
        $query = DB::table('timesheet_approval')
            ->join('timesheets', 'timesheet_approval.timesheet_id', '=', 'timesheets.id')
            ->join('users', 'timesheet_approval.user_id', '=', 'users.id')
            ->leftJoin('timesheet_notes', function($join) {
                $join->on('timesheets.id', '=', 'timesheet_notes.timesheet_id')
                     ->on('timesheet_approval.user_id', '=', 'timesheet_notes.user_id');
            })
            ->leftjoin('timesheet_reports', function($join){
                $join->on('timesheets.id', '=', 'timesheet_reports.timesheet_id')
                     ->on('timesheet_reports.user_id', '=', 'timesheet_approval.user_id');
            })
            ->select(
                'users.name as user_name',
                'timesheets.month',
                'timesheets.year',
                DB::raw('SUM(timesheet_reports.regular_hours) as total_hours'),
                'timesheet_approval.status',
            );

        // if (!empty($this->search)) {
        //     $query->where('users.name', 'like', '%' . $this->search . '%');
        // }

        if ($this->month != '0') {
            $query->where('timesheets.month', '=', $this->month);
        }

        if ($this->year != '0') {
            $query->where('timesheets.year', '=', $this->year);
        }

        if (!is_null($this->status)) {
           $query->where('timesheet_approval.status', '=', $this->status);
        }

       


        $query->groupBy(
            'timesheet_approval.id',
            'users.name',
            'timesheets.month',
            'timesheets.year',
            'timesheet_approval.status',
            // 'timesheet_notes.attachment'
        );

       $results = $query->get();
    return $results->map(function ($item) {
        $statusMap = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
        ];

        $item->status = $statusMap[$item->status] ?? 'Unknown';
        return $item;
    });

    }

    public function headings(): array
    {
        return [
            'User',
            'Month',
            'Year',
            'Total Hours',
            'Status',
            // 'Attachment',
        ];
    }
}


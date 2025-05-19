<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Timesheet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                    ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours'
                        
                    )
                    // ->groupBy('timesheet_reports.timesheet_id') 
                    // ->groupBy('timesheet_reports.user_id') 
                    ->get();   
                    
                    // return $timesheets = DB::table('timesheets')
                    // ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    // ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                    // ->select(
                    //     'users.name as user_name',
                    //     'timesheets.month', 
                    //     DB::raw('SUM(timesheet_reports.regular_hours) as reg_hours'),
                        
                    // )
                    // ->groupBy('timesheet_reports.timesheet_id') 
                    // ->groupBy('timesheet_reports.user_id') 
                    // ->get();   
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
            'Month',
            'Task',
            'Hours',
        ];
    }
}

<?php

namespace App\Exports;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Timesheet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExportByFilter implements FromCollection, WithHeadings
{
    protected $user,$client,$month,$year;

    public function __construct($user,$client,$month,$year)
    {
        $this->user = $user;
        $this->client = $client;
        $this->month = $month;
        $this->year = $year;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->user != 0 && $this->client == 0)
        {
              return $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                    ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->where('timesheet_reports.user_id', '=', $this->user)
                    ->where('timesheets.month', '=' , $this->month)
                    ->where('timesheets.year', '=' , $this->year)
                    ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours',
                         'timesheet_reports.date'
                        
                    )
                  //  ->groupBy('timesheet_reports.timesheet_id') 
                 //   ->groupBy('timesheet_reports.user_id') 
                    ->get();   
        }else if($this->user == 0 && $this->client != 0)
        {
              return $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                    ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->where('timesheet_reports.client_id', '=', $this->client)
                    ->where('timesheets.month', '=' , $this->month)
                    ->where('timesheets.year', '=' , $this->year)
                   ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours',
                         'timesheet_reports.date'
                        
                    )
                   // ->groupBy('timesheet_reports.timesheet_id') 
                  //  ->groupBy('timesheet_reports.user_id') 
                    ->get();   
        }else{
              return $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                    ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->where('timesheets.month', '=' , $this->month)
                    ->where('timesheets.year', '=' , $this->year)
                   ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours',
                        'timesheet_reports.date'
                    )
                    //->groupBy('timesheet_reports.timesheet_id') 
                   // ->groupBy('timesheet_reports.user_id') 
                    ->get();  
        }
      
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        // Define column headings
        return [
            'User',
            'client',
            'Month',
            'Task',
            'Hours',
            'Date'
        ];
    }
}

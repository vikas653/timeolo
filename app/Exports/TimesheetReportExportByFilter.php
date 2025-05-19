<?php

namespace App\Exports;

use App\Models\TimesheetReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TimesheetReportExportByFilter implements FromCollection, WithHeadings, WithMapping
{
    protected $user;
    protected $client;
    protected $month;
    protected $year;
    protected $timesheet_id;

    public function __construct($user,$client,$month,$year,$timesheet_id)
    {
        $this->user = $user;
        $this->client = $client;
        $this->month = Carbon::parse("1 $month")->format('n');
        $this->year = $year;
        $this->timesheet_id = $timesheet_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $data = TimesheetReport::select('date', 'client_id', 'user_id', 'activity', 'regular_hours','date')
            ->where('user_id', $this->user)
            ->where('client_id', $this->client)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('timesheet_id', $this->timesheet_id)
             ->whereHas('client')->whereHas('user')
            ->get();

        
        return $data;
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
            'Date'
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
            $row->date,
        ];
    }
}

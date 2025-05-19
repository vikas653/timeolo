<?php

namespace App\Exports;

use App\Models\TimesheetReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetReportExport2 implements FromCollection, WithHeadings, WithMapping
{
    protected $id;
    protected $timesheet_id;

    public function __construct($id, $timesheet_id)
    {
        $this->id = $id;
        $this->timesheet_id = $timesheet_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TimesheetReport::select('date','client_id','user_id','activity', 'regular_hours','date')
            ->where('user_id', $this->id)
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
            'Hours'
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

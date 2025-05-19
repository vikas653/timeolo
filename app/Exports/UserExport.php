<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\ProjectAsign;
use App\Models\Project;


use Carbon\Carbon;

class UserExport implements FromCollection,WithHeadings,WithEvents
{
    protected  $users;
    protected  $selects;
    protected  $selects2;
    protected  $selects3;
    protected  $row_count;
    protected  $column_count;
    protected $id;

    
    public function __construct($id)
    {



        $this->id = $id;
        $user_array = User::where('role_id',2)->pluck('name')->toArray();
       
        $record = Timesheet::where('id',$this->id)->first();

        $projectAsign = ProjectAsign::where('user_id', auth()->id())->pluck('project_id')->toArray();
        $project = Project::whereIn('id', $projectAsign)->pluck('name')->toArray();
        
        $monthName = $record->month;
        $yearName = $record->year;
        $year = date('Y');


        $daysInMonth = Carbon::createFromDate($year, Carbon::parse("1 $monthName")->month)->daysInMonth;

        $dates = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = Carbon::create($yearName, Carbon::parse("1 $monthName")->month, $day, 0, 0, 0);
            
            $formattedDate = $date->format('m/d/Y');
            
            $dates[] = $formattedDate;
        }

        $selects=[  //selects should have column_name and options
            ['columns_name'=>'A','options'=>$user_array]
        ];

        $selects2=[  //selects should have column_name and options
            ['columns_name'=>'B','options'=>$dates]
        ];

        $selects3=[
            ['columns_name'=> 'F', 'options'=> $project]
        ];
        $this->selects=$selects;
        $this->row_count=100;//number of rows that will have the dropdown
        $this->column_count=3;//number of columns to be auto sized

        $this->selects2=$selects2;
        $this->row_count=100;//number of rows that will have the dropdown
        $this->column_count=3;//number of columns to be auto sized

        $this->selects3=$selects3;
        $this->row_count=100;
        $this->column_count=3;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([]);
    }
    
    public function headings(): array
    {
        return [
            ['Please use the dropdowns to select the client, date, and project name. Manually entering values may lead to errors.'], 
            ['client', 'date', 'activity', 'regular_hours', 'billable (0/1)', 'Project Name'] 
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            
            AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'Please use the dropdowns to select the client, date, and project name. Manually entering values may lead to errors.');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true); 
        $sheet->getRowDimension('1')->setRowHeight(40); 
                $row_count = $this->row_count;
                $column_count = $this->column_count;
    
                $hiddenSheet = $event->sheet->getDelegate()->getParent()->createSheet();
                $hiddenSheet->setTitle('Hidden');
                $hiddenSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
    
               
                $drop_column = $this->selects[0]['columns_name'];
                $options = $this->selects[0]['options'];

                // Populate hidden sheet with dropdown values
                foreach ($options as $index => $option) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1) . ($index + 1);
                    $hiddenSheet->setCellValue($cellCoordinate, $option);
                }

                // Set data validation formula to refer to hidden sheet cells
                $validation = $event->sheet->getCell("{$drop_column}3")->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Hidden!$A$1:$A$' . count($options));

                // Clone validation to remaining rows
                for ($i = 4; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                }

                // Set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
              

                $hiddenSheet2 = $event->sheet->getDelegate()->getParent()->createSheet();
                $hiddenSheet2->setTitle('Hidden2');
                $hiddenSheet2->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
    
               
                $drop_column = $this->selects2[0]['columns_name'];
                $options =  $this->selects2[0]['options'];
                
                // Populate hidden sheet with dropdown values
                foreach ($options as $index => $option) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1) . ($index + 1);
                    $hiddenSheet2->setCellValue($cellCoordinate, $option);
                }

                // Set data validation formula to refer to hidden sheet cells
                $validation = $event->sheet->getCell("{$drop_column}3")->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Hidden2!$A$1:$A$' . count($options));

                // Clone validation to remaining rows
                for ($i = 4; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                }

                // Set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
              

                $hiddenSheet3 = $event->sheet->getDelegate()->getParent()->createSheet();
                $hiddenSheet3->setTitle('Hidden3');
                $hiddenSheet3->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
    
               
                $drop_column = $this->selects3[0]['columns_name'];
                $options =  $this->selects3[0]['options'];
                
                // Populate hidden sheet with dropdown values
                foreach ($options as $index => $option) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1) . ($index + 1);
                    $hiddenSheet3->setCellValue($cellCoordinate, $option);
                }

                // Set data validation formula to refer to hidden sheet cells
                $validation = $event->sheet->getCell("{$drop_column}3")->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Hidden3!$A$1:$A$' . count($options));

                // Clone validation to remaining rows
                for ($i = 4; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                }

                // Set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}

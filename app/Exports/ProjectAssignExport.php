<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
class ProjectAssignExport implements FromArray, FromCollection,WithHeadings,WithEvents
{
    protected  $users;
    protected  $selects;
    protected  $selects2;
    protected  $row_count;
    protected  $column_count;
    protected $id;

    public function __construct()
    {
        // $this->id = $id;
        $user_array = User::where('role_id',3)->pluck('name')->toArray();
       
        $dates = Project::pluck('code');
       
       

        $selects=[  //selects should have column_name and options
            ['columns_name'=>'A','options'=>$user_array]
        ];

        $selects2=[  //selects should have column_name and options
            ['columns_name'=>'B','options'=>$dates]
        ];
        $this->selects=$selects;
        $this->row_count=100;//number of rows that will have the dropdown
        $this->column_count=3;//number of columns to be auto sized

        $this->selects2=$selects2;
        $this->row_count=100;//number of rows that will have the dropdown
        $this->column_count=3;//number of columns to be auto sized
    }
    public function array(): array
    {
        return [
           
        ];
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
        return ['User', 'Project Name'];
    }
// }
  /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
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
                $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Hidden!$A$1:$A$' . count($options));

                // Clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
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
                $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Hidden2!$A$1:$A$' . count($options));

                // Clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
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


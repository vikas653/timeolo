<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetNotes extends Model
{

    use HasFactory;
    protected $table='timesheet_notes';

    protected $fillable = [
        'id','notes','attachment','timesheet_id','user_id','approver_name','approver_email'
  ];

}

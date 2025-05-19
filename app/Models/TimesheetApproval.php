<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetApproval extends Model
{

    use HasFactory;
    protected $table='timesheet_approval';

    protected $fillable = [
        'id','status','timesheet_id','user_id','approved_at','reason'
  ];

}

?>

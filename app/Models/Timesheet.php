<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{

    use HasFactory;
    protected $fillable = [
        'id' ,'month','year','unique_id','total_hours','notes'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillStatus extends Model
{
    protected $table = 'bill_status';

    protected $fillable = [
        'user_id',
        'timesheet_id',
        'client_id',
        'project_id',
        'quantity',
        'pay_rate',
        'total_amount',
        'status',
    ];
}
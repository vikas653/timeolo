<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $table = 'invoice_status';

    protected $fillable = [
        'user_id',
        'timesheet_id',
        'client_id',
        'project_id',
        'hours',
        'project_bill_rate',
        'total_amount',
        'status',
    ];
}
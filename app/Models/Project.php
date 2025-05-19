<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    use HasFactory;
    protected $table='project';

    protected $fillable = [
        'id' ,'code','name','user_id','start','end','bill_rate','approver_name','approver_email','approver_phone','bill_by','pay_rate_currency','bill_rate_unit','terms_id'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function term()
{
    return $this->belongsTo(Terms::class, 'terms_id');
}
}

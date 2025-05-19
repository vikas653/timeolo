<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetReport extends Model
{

    use HasFactory;
    protected $fillable = [
        'id','timesheet_id','client_id','user_id','date','activity','regular_hours','approver_name','approver_email','code','billable','remark','summary'
  ];
  public function client()
    {
        return $this->hasOne(User::class,'id','client_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function project(){
        return $this->hasOne(Project::class, 'id', 'code');
    }


}

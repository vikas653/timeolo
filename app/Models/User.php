<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
      'id','name' ,'mobile_no' ,'address','role_id','email','password','client_id','created_by','approver_name','approver_email','pay_rate','currency','job_title','dob','ssn'
      ,'employment_id','system_access_id','start_date','end_date','bank_name','bank_branch_address','account_holder_name','bank_account_number','swift_bic_code','iban_number','bank_routing_number','pay_rate_currency','approver_phone','work_email','vendor_id','location'
  ];
  public function employment()
{
    return $this->belongsTo(Employment::class, 'employment_id');
}

public function system()
{
    return $this->belongsTo(SystemAccess::class, 'system_access_id');
}

}

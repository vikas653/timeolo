<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Vendors extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
      'id','company_name','first_name','last_name' ,'phone' ,'address','email'
      ,'bank_name','bank_branch_address','account_holder_name','bank_account_number','swift_bic_code','iban_number','bank_routing_number', 'tax_id_number','terms','consultant_name','pay_rate','pay_rate_currency','created_by','user_id'
  ];
 
}

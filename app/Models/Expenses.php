<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
  protected  $table= 'expenses';

  protected $fillable = ['destination','amount','travel_date','receipts','purpose','user_id','status','approved_by','approved_at'];

public function user(){
    return $this->hasone(User::class, 'id','user_id');
}


 
}

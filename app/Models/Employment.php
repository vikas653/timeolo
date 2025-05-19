<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{

    use HasFactory;
    protected $table='type_employment';

    protected $fillable = [
        'id','name','user_id'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}

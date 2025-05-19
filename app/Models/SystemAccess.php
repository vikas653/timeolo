<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAccess extends Model
{

    use HasFactory;
    protected $table='type_system_access';

    protected $fillable = [
        'id','name','user_id'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}

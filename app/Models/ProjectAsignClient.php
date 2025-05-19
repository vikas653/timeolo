<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAsignClient extends Model
{

    use HasFactory;
    protected $table='project_asign_client';

    protected $fillable = [
        'id' ,'client_id','project_id','start' ,'end'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','client_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAsign extends Model
{

    use HasFactory;
    protected $table='project_asign';

    protected $fillable = [
        'id' ,'user_id','project_id','start' ,'end'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }
}

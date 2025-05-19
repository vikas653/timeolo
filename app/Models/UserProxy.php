<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProxy extends Model {
    use HasFactory;

    protected $fillable = ['proxy_id', 'target_user_id'];

    public function proxyUser() {
        return $this->belongsTo(User::class, 'proxy_id');
    }

    public function targetUser() {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}


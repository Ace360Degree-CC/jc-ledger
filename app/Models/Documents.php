<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Documents extends Authenticatable
{

    protected $fillable = [
        'ko_code',
        'certificate_id',
        'certificate_name',
        'status',
        'verified',
        'message'
    ];
    
}

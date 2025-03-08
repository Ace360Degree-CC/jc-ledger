<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Subadmin extends Authenticatable
{
    protected $table = 'subadmin';
    protected $guard = 'subadmin';
    protected $fillable = ['name', 'email', 'password','username','profile','status'];
    protected $hidden = ['password'];

    public function excelLogs(){
        return $this->hasMany(ExcelLog::class, 'uploadedBy', 'id');
    }

}


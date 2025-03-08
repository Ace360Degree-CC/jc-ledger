<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $guard = 'admin';
    protected $fillable = ['name', 'email', 'profile','password','username','status'];
    protected $hidden = ['password'];

    public function excelLogs(){
        return $this->hasMany(ExcelLog::class, 'uploadedBy', 'id');
    }

}


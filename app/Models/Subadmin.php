<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Subadmin extends Authenticatable
{
    protected $table = 'subadmin';
    protected $guard = 'subadmin';
    protected $fillable = ['name', 'email', 'password','username','status'];
    protected $hidden = ['password'];
}



// class Admin extends Authenticatable
// {
//     protected $table = 'admin';
//     protected $guard = 'admin';
//     protected $fillable = ['name', 'email', 'password','username','status'];
//     protected $hidden = ['password'];

// }

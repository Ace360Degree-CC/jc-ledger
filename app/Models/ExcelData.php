<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ExcelData extends Authenticatable
{
    protected $table = 'excel_data';
    protected $guard = 'admin';
}

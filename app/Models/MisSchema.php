<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MisSchema extends Model
{
    use HasFactory;

    protected $table = 'mis_schema';

    protected $fillable = [
        'column',
        'name',
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MisData extends Model
{
    use HasFactory;

    protected $table = 'mis_data';

    // If you truly have columns B..BF
    // You can define them in fillable if you want mass assignment:
        protected $fillable = [
            // B through Z
            'B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    
            // AA through BF
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP',
            'AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
            'BA','BB','BC','BD','BE','BF',
        ];
}

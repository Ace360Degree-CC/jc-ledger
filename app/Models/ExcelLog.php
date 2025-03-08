<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelLog extends Model
{
    protected $table = 'excel_log';
    protected $guard = 'admin';

    public function admin(){
        return $this->belongsTo(Admin::class, 'uploadedBy', 'id');
    }

    public function subadmin(){
        return $this->belongsTo(Subadmin::class, 'uploadedBy', 'id');
    }

     // Get the uploader's name dynamically
     public function getUploaderNameAttribute()
     {
         if ($this->uploadFrom == 'Admin') {
             return $this->admin->name ?? 'Unknown Admin';
         } else if ($this->uploadFrom == 'Subadmin') {
             return $this->subadmin->name ?? 'Unknown Subadmin';
         }
         
         return 'Unknown User';
     }

}

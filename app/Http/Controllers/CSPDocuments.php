<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSPDocuments extends Controller
{
    //

    public function  engagementCertificate(){
        return view('certificate/view');
    }

    public function cspIdentity(){
        return view('certificate/identity');
    }

}

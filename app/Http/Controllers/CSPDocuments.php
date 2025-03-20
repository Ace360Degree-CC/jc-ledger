<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Documents;

class CSPDocuments extends Controller
{
    public function index(){

        $cspKo_Code = Auth::guard('web')->user()->ko_code;
        $cspDocuments = Documents::where('ko_code',$cspKo_Code)->get()->toArray();
        //print_r("<pre>");print_r($documents);exit;
        return view('certificate/index',compact('cspDocuments'));
    }

    public function  engagementCertificate(){

        $crntUserID = Auth::guard('web')->user()->id;
        $myProfile = User::findOrFail($crntUserID);
        //ko_code, name, profile
        // print_r("<pre>");print_r($myProfile['ko_code']);exit;

        return view('certificate/view',compact('myProfile'));
    }

    public function cspIdentity(){
        return view('certificate/identity');
    }


    public function requestCertificate(Request $request)
    {
        // Validate the request
        $request->validate([
            'certificate_id' => 'required|integer|between:1,3',
        ]);
        
        $certificateId = $request->certificate_id;
        $certificateName = $request->certificate_name;
        $cspKo_Code = Auth::guard('web')->user()->ko_code;
        
        // Check if this certificate already exists for this user
        $existing = Documents::where('ko_code', $cspKo_Code)
            ->where('certificate_id', $certificateId)
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate has already been requested.'
            ]);
        }
        
        // Create new document request
        $document = Documents::create([
            'ko_code' => $cspKo_Code,
            'certificate_id' => $certificateId,
            'certificate_name' => $certificateName,
            'status' => 'pending',
            'verified' => 0,
            'message' => 'CSP agent requests certificate'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Certificate request submitted successfully.'
        ]);
    }
    
    public function viewCertificate($id)
    {
        // Get the document
        $document = Documents::findOrFail($id);
        
        // Check if user is authorized to view this document
        if (Auth::guard('web')->user()->ko_code !== $document->ko_code) {
            abort(403, 'Unauthorized');
        }
        
        // Get certificate file path (assuming it's stored somewhere)
        $filePath = $this->getCertificateFilePath($document);
        
        // Check if file exists
        if (!Storage::exists($filePath)) {
            return back()->with('error', 'Certificate file not found.');
        }
        
        // Return view with file data
        return response()->file(storage_path('app/' . $filePath));
    }


}

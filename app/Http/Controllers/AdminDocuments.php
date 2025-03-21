<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\MisData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminDocuments extends Controller
{


    /**
 * Update documents status, verification, and messages
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function updateDocuments(Request $request)
{
    // Validate the request
    $request->validate([
        'csp_id' => 'required|exists:users,id',
        'verified' => 'array',
        'status' => 'array',
        'reason' => 'array'
    ]);
    
    $cspId = $request->input('csp_id');
    $csp = User::findOrFail($cspId);
    $koCode = $csp->ko_code;
    
    // List of certificate IDs
    $certificateIds = [1, 2, 3]; // ID-Card, BC Certificate, BC Agreement
    $certificateNames = [
        1 => 'ID-Card',
        2 => 'BC Certificate',
        3 => 'BC Agreement'
    ];
    
    foreach ($certificateIds as $certId) {
        // Skip if status is not selected in the form
        if (!isset($request->status[$certId]) || empty($request->status[$certId])) {
            continue;
        }
        
        // Get verified status, document status, and reason
        $verified = isset($request->verified[$certId]) ? 1 : 0;
        $status = $request->status[$certId];
        $message = $request->reason[$certId] ?? '';
        
        // Check if document already exists for this ko_code and certificate_id
        $existing = Documents::where('ko_code', $koCode)
                            ->where('certificate_id', $certId)
                            ->first();
        
        if ($existing) {
            // Update existing record
            $existing->certificate_name = $certificateNames[$certId];
            $existing->status = $status;
            $existing->verified = $verified;
            $existing->message = $message;
            $existing->save();
        } else {
            // Only create new record if status is not empty
            if (!empty($status) && $status !== 'Select') {
                Documents::create([
                    'ko_code' => $koCode,
                    'certificate_id' => $certId,
                    'certificate_name' => $certificateNames[$certId],
                    'status' => $status,
                    'verified' => $verified,
                    'message' => $message
                ]);
            }
        }
    }
    
    return redirect()->back()->with('success', 'Documents updated successfully');
}


    public function documents($documentType, $koCode)
    {
        // Get the CSP profile based on ko_code
        $myProfile = User::where('ko_code', $koCode)->first();

        // If the profile is not found
        if (!$myProfile) {
            return response("CSP Agent profile not found with KO ID is $koCode", 404);
        }

        // Get the profile image filename (with default fallback)
        $myProfileArray = $myProfile->toArray();
        $profile = $myProfileArray['profile'] ?? null;
        $signature = $myProfileArray['signature'] ?? null;
        $pancard = $myProfileArray['pancard'] ?? null;

        $agreement = $myProfileArray['bc_agreement'] ?? null;

        // Determine which document to serve based on the documentType
        if ($documentType == 'photo' && $profile) {
            // Construct the full path to the profile image in the storage directory
            $path = storage_path('app/public/csp/profile/' . $profile);
            return $this->serveFile($path, 'Profile image not found');
        } elseif ($documentType == 'signature' && $signature) {
            // Construct the full path to the signature image in the storage directory
            $path = storage_path('app/public/csp/signature/' . $signature);
            return $this->serveFile($path, 'Signature not found');
        } elseif ($documentType == 'pancard' && $pancard) {
            // Construct the full path to the pancard image in the storage directory
            $path = storage_path('app/public/csp/pancard/' . $pancard);
            return $this->serveFile($path, 'Pancard not found');
        }

        elseif ($documentType == 'agreement' && $agreement) {
            // Construct the full path to the agreement in the storage directory
            $path = storage_path('app/public/csp/agreements/' . $agreement);
            return $this->serveFile($path, 'Agreement not found');
        }

        // If document type is invalid or file is not available
        return response("Document type '$documentType' not available for KO Code: $koCode", 404);
    }

    /**
     * Helper function to check file existence and serve it
     */
    private function serveFile($path, $errorMessage)
    {
        // Check if the file exists in the filesystem
        if (!file_exists($path)) {
            return response($errorMessage, 404);
        }

        // Return the image file directly to the browser
        return response()->file($path);
    }


    public function BC_IDCard($koCode)
    {
        // print_r($koCode);exit;
        // $crntUserID = Auth::guard('web')->user()->id;

        $myProfile = User::where('ko_code', $koCode)->first();

        if (!$myProfile) {
            return "CSP Agent profile not found with KO ID is $koCode";
        }

        $misData = MisData::where('C', $koCode)->first();

        if (!$misData) {
            return "MIS Excel sheet data not found for user KO ID is $koCode";
        }

        // Proceed with further processing if both $myProfile and $misData are found
        // For example, you can convert them to arrays or pass them to a view
        $myProfileArray = $myProfile->toArray();
        $misDataArray = $misData->toArray();

        $data['ko_id'] = $misData['C'];

        $namePrefix = '';
        if ($misData['W'] == 'M') {
            $namePrefix = 'Mr. ';
        } elseif ($misData['W'] == 'F') {
            $namePrefix = 'Ms. ';
        }

        if (isset($misData['D']) && !empty($misData['D'])) {
            $data['bc_name'] = $namePrefix . $misData['D'];
        }

        if (isset($misData['C']) && !empty($misData['C'])) {
            $data['bc_id'] = $misData['C'];
        }

        if (isset($misData['P']) && !empty($misData['P'])) {
            $data['bc_location'] = $misData['P'];
        }

        if (isset($misData['U']) && !empty($misData['U'])) {
            $data['branch_code'] = $misData['U'];
        }

        if (isset($misData['T']) && !empty($misData['T'])) {
            $data['branch_name'] = $misData['T'];
        }

        if (isset($misData['X']) && !empty($misData['X'])) {
            $data['bc_mobile'] = $misData['X'];
        }

        // Convert profile image to base64
        if (isset($myProfile['profile']) && !empty($myProfile['profile'])) {
            $profilePath = storage_path('app/public/csp/profile/'.$myProfile['profile']);
            if (file_exists($profilePath)) {
                $imageData = file_get_contents($profilePath);
                $base64Image = base64_encode($imageData);
                $data['profile_base64'] = 'data:image/jpeg;base64,' . $base64Image;
            }
        }
        
        // Convert signature to base64
        if (isset($myProfile['signature']) && !empty($myProfile['signature'])) {
            $signaturePath = storage_path('app/public/csp/signature/'.$myProfile['signature']);
            if (file_exists($signaturePath)) {
                $signatureData = file_get_contents($signaturePath);
                $base64Signature = base64_encode($signatureData);
                $data['signature_base64'] = 'data:image/jpeg;base64,' . $base64Signature;
            }
        }
        
        // Convert BOI logo to base64
        $boiLogoPath = public_path('assets/images/logos/boi-logo.png');
        if (file_exists($boiLogoPath)) {
            $boiLogoData = file_get_contents($boiLogoPath);
            $base64BoiLogo = base64_encode($boiLogoData);
            $data['boi_logo_base64'] = 'data:image/png;base64,' . $base64BoiLogo;
        }
        
        // Convert JC logo to base64
        $jcLogoPath = public_path('assets/images/logos/jc-logo.png');
        if (file_exists($jcLogoPath)) {
            $jcLogoData = file_get_contents($jcLogoPath);
            $base64JcLogo = base64_encode($jcLogoData);
            $data['jc_logo_base64'] = 'data:image/png;base64,' . $base64JcLogo;
        }
        
        // Convert JC signature to base64
        $jcSignaturePath = public_path('assets/images/signature/jc_ledger_id_sign.jpg');
        if (file_exists($jcSignaturePath)) {
            $jcSignatureData = file_get_contents($jcSignaturePath);
            $base64JcSignature = base64_encode($jcSignatureData);
            $data['jc_signature_base64'] = 'data:image/jpeg;base64,' . $base64JcSignature;
        }

        // print_r("<pre>");print_r($data);exit;

        return view('certificate/identity')->with($data);
    }


    public function BCCertificate($koCode)
    {
        // print_r($koCode);exit;
        // $crntUserID = Auth::guard('web')->user()->id;

        $myProfile = User::where('ko_code', $koCode)->first();

        if (!$myProfile) {
            return "CSP Agent profile not found with KO ID is $koCode";
        }

        $misData = MisData::where('C', $koCode)->first();

        if (!$misData) {
            return "MIS Excel sheet data not found for user KO ID is $koCode";
        }

        // Proceed with further processing if both $myProfile and $misData are found
        // For example, you can convert them to arrays or pass them to a view
        $myProfileArray = $myProfile->toArray();
        $misDataArray = $misData->toArray();

        $data['ko_id'] = $misData['C'];

        $namePrefix = '';
        if ($misData['W'] == 'M') {
            $namePrefix = 'Mr. ';
        } elseif ($misData['W'] == 'F') {
            $namePrefix = 'Ms. ';
        }

        if (isset($misData['D']) && !empty($misData['D'])) {
            $data['ko_name'] = $namePrefix . $misData['D'];
        }

        if (isset($misData['N']) && !empty($misData['N'])) {
            $data['zone'] = $misData['N'];
        }

        if (isset($misData['AQ']) && !empty($misData['AQ'])) {
            $data['agreement_date'] = $misData['AQ'];
        }

        // if (isset($myProfile['profile']) && !empty($myProfile['profile'])) {
        //     $data['profile'] = $myProfile['profile'];
        // }

        // Convert profile image to base64
        if (isset($myProfile['profile']) && !empty($myProfile['profile'])) {
            $profilePath = storage_path('app/public/csp/profile/'.$myProfile['profile']);
            if (file_exists($profilePath)) {
                $imageData = file_get_contents($profilePath);
                $base64Image = base64_encode($imageData);
                $data['profile_base64'] = 'data:image/jpeg;base64,' . $base64Image;
            }
        }

        // Convert JC logo to base64
        $jcLogoPath = public_path('assets/images/certificates/jc-certificate-logo.PNG');
        if (file_exists($jcLogoPath)) {
            $jcLogoData = file_get_contents($jcLogoPath);
            $base64JcLogo = base64_encode($jcLogoData);
            $data['jc_logo_base64'] = 'data:image/png;base64,' . $base64JcLogo;
        }


        // Convert certificate footer stamp to base64
        $jcLogoPath = public_path('assets/images/certificates/business-correspondent.PNG');
        if (file_exists($jcLogoPath)) {
            $jcLogoData = file_get_contents($jcLogoPath);
            $base64JcLogo = base64_encode($jcLogoData);
            $data['certificate_footer_base64'] = 'data:image/png;base64,' . $base64JcLogo;
        }

        // print_r("<pre>");print_r($data);exit;

        return view('certificate/view')->with($data);
    }

    public function uploadAgreement(Request $request)
    {
        // Validate the request
        $request->validate([
            'ko_code' => 'required|exists:users,ko_code',
            'agreement_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:30720',  // 30MB
            'agreement_date' => 'required|date'
        ]);

        $koCode = $request->input('ko_code');

        // Store the agreement file
        $filename = time() . '_' . $koCode . '_agreement.' . $request->file('agreement_file')->extension();

        $path = $request->file('agreement_file')->storeAs('csp/agreements', $filename, 'public');
        
        // Update or create document record
        $document = Documents::updateOrCreate(
            [
                'ko_code' => $koCode,
                'certificate_id' => 3  // BC Agreement
            ],
            [
                'certificate_name' => 'BC Agreement',
                'status' => 'pending',  // Set initial status as pending
                'verified' => 0,
                'message' => 'Agreement uploaded on ' . date('Y-m-d') . '. Agreement date: ' . $request->input('agreement_date')
            ]
        );

        // Store the agreement file path in users table
        User::where('ko_code', $koCode)->update([
            'bc_agreement' => $filename
        ]);

        // Store agreement date in misdata table
        MisData::where('C', $koCode)->update([
            'AQ' => $request->input('agreement_date')
        ]);

        return redirect()->back()->with('success', 'Agreement uploaded successfully');
    }
}

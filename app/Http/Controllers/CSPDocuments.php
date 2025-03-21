<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\MisData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CSPDocuments extends Controller
{
    public function index()
    {
        $cspKo_Code = Auth::guard('web')->user()->ko_code;
        $cspDocuments = Documents::where('ko_code', $cspKo_Code)->get()->toArray();
        // print_r("<pre>");print_r($cspDocuments);exit;
        return view('certificate/index', compact('cspDocuments'));
    }

    public function engagementCertificate()
    {
        $crntUserID = Auth::guard('web')->user()->id;
        $myProfile = User::findOrFail($crntUserID);
        // ko_code, name, profile
        // print_r("<pre>");print_r($myProfile['ko_code']);exit;

        return view('certificate/view', compact('myProfile'));
    }

    public function cspIdentity()
    {
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

    // //////
    // //NEW FUNCTIONS FOR DOCUMENTS FROM CSP AGENT SIDE
    // /////

    public function viewCertificate($id)
    {
        // Example logic to determine which route to redirect to
        if ($id == 1) {
            // Redirect to a ID Card Route
            return redirect()->route('document.identity');
        } elseif ($id == 2) {
            // Redirect to BC Certificate Route
            return redirect()->route('document.certificate');
        } elseif ($id == 3) {
            // Redirect to BC Agreement Route
            return redirect()->route('document.agreement');
        }

        // If no condition is met, return the default view or error
        return response('Invalid ID', 404);
    }

    public function BC_IDCard()
    {
        // print_r($koCode);exit;
        // $crntUserID = Auth::guard('web')->user()->id;
        $koCode = Auth::guard('web')->user()->ko_code;
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

    public function BCCertificate()
    {
        // print_r($koCode);exit;
        // $crntUserID = Auth::guard('web')->user()->id;
        $koCode = Auth::guard('web')->user()->ko_code;
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

    public function BC_Agreement()
    {
        $koCode = Auth::guard('web')->user()->ko_code;
        $myProfile = User::where('ko_code', $koCode)->first()->toArray();

        $path = storage_path('app/public/csp/agreements/' . $myProfile['bc_agreement']);
        // Open the agreement in browser
        return response()->file($path);
    }

    public function downloadCertificate($id)
    {
        // Example logic to determine which route to redirect to
        if ($id == 1) {
            // Redirect to a ID Card Route
            return redirect()->route('download.document.identity');
        } elseif ($id == 2) {
            // Redirect to BC Certificate Route
            return redirect()->route('download.document.certificate');
        } elseif ($id == 3) {
            // Redirect to BC Agreement Route
            return redirect()->route('download.document.agreement');
        }

        // If no condition is met, return the default view or error
        return response('Invalid ID', 404);
    }



    
    public function Download_BC_IDCard()
    {
        // Get user data
        $koCode = Auth::guard('web')->user()->ko_code;
        $myProfile = User::where('ko_code', $koCode)->first();
        if (!$myProfile) {
            return "CSP Agent profile not found with KO ID is $koCode";
        }
        $misData = MisData::where('C', $koCode)->first();
        if (!$misData) {
            return "MIS Excel sheet data not found for user KO ID is $koCode";
        }
    
        // Prepare data for view
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
    
        // Generate the HTML content from the view
        $html = view('certificate/identity')->with($data)->render();
        
        // Set filename with user ID
        $filename = 'BC_IDCard_' . $data['bc_id'] . '.pdf';
    
        // Initialize DomPDF with better options for ID cards
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
    
        // Set a custom paper size for ID cards (500pt x 700pt)
        // This provides a better aspect ratio for ID cards
        $dompdf->setPaper(array(0, 0, 500, 700));
    
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }


    public function Download_BCCertificate()
    {
        // print_r($koCode);exit;
        // $crntUserID = Auth::guard('web')->user()->id;
        $koCode = Auth::guard('web')->user()->ko_code;
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

        //return view('certificate/view')->with($data);

        // Generate the HTML content from the view
        $html = view('certificate/view')->with($data)->render();
        
        // Set filename with user ID
        $filename = 'BC_Certificate_' . $data['ko_id'] . '.pdf';
    
        // Initialize DomPDF with better options for ID cards
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
    
        // Set a custom paper size for ID cards (500pt x 700pt)
        // This provides a better aspect ratio for ID cards
        $dompdf->setPaper(array(0, 0, 500, 700));
    
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function Download_BC_Agreement()
    {
        $koCode = Auth::guard('web')->user()->ko_code;
        $myProfile = User::where('ko_code', $koCode)->first()->toArray();

        $path = storage_path('app/public/csp/agreements/' . $myProfile['bc_agreement']);
        // Open the agreement in browser
        return response()->download($path);
    }

}

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

        if (isset($myProfile['profile']) && !empty($myProfile['profile'])) {
            $data['profile'] = $myProfile['profile'];
        }

        // print_r("<pre>");print_r($data);exit;

        return view('certificate/view')->with($data);
    }
}

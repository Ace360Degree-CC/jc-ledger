<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Subadmin;
use App\Models\User;
use App\Models\Documents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view('admin.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Show registration form
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:10|unique:admin,username',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:6|confirmed',
        ]);
        
        Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1,
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful! Please login.');
    }



    


    // Show dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Handle logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function showcreateSubadmin(){
        return view('admin.subadmin.addForm');
    }

    public function createSubadmin(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:subadmin,username',
            'email' => 'required|email|unique:subadmin,email',
            'password' => 'required|min:6|confirmed',
            'profile'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // If you're allowing images
            'status'    => 'nullable|in:0,1'
        ]);
        
        // Handle profile image if exists
        if ($request->hasFile('profile')) {
            // Store the file and get the paths
            $profile = $request->file('profile')->storeAs('subadmin/profile',$request->username.'_'.time().'.'.$request->file('profile')->getClientOriginalExtension(),'public');
            //print_r($profile);exit;
        } else {
            // If no file uploaded, retain the old profile image
            $profile = '';
        }

        Subadmin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status??0,
            'profile' => $profile
        ]);
        
        return redirect()
               ->route('admin.allSubadmins')   // <-- Make sure this is the correct route name for your subadmin list
               ->with('success', $request->name . " created successfully!");

    }

    public function editSubadmin($id)
    {
        $subadmin = SubAdmin::find($id);
        // Pass the $subadmin variable to the view
        return view('admin.subadmin.editForm', compact('subadmin'));
    }

    public function updateSubadmin(Request $request)
    {
        $id = $request->id;
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:subadmin,username,' . $id, // Prevent unique constraint conflict on the current subadmin
            'email' => 'required|email|unique:subadmin,email,' . $id,
            'password' => 'nullable|min:6', 
        ]);
    
        // Find the subadmin by ID
        $subadmin = Subadmin::findOrFail($id);
    
        // Handle profile image if exists
        if ($request->hasFile('profile')) {
            // Store the file and get the paths
            $profile = $request->file('profile')->storeAs('subadmin/profile',$request->username.'_'.time().'.'.$request->file('profile')->getClientOriginalExtension(),'public');
            //print_r($profile);exit;
        } else {
            // If no file uploaded, retain the old profile image
            $profile = $subadmin->profile;
        }
    
        // Update the subadmin's data
        $subadmin->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $subadmin->password, // Update password only if provided
            'status' => ($request->status)?1:0,
            'profile' => $profile,
        ]);
    
        // Return a success response
        // return response()->json([
        //     'message' => 'Subadmin updated successfully.',
        //     'data' => $subadmin
        // ], 200);

        // Redirect to the subadmins index page with a success message
        return redirect()
               ->route('admin.allSubadmins')   // <-- Make sure this is the correct route name for your subadmin list
               ->with('success', $subadmin->name. ' updated successfully.');

    }
    

    public function deleteSubadmin(Request $request)
{
    // Get the subadmin id from the request
    $subadmin_id = $request->input('id');
    // Find the subadmin by the id
    $subadmin = Subadmin::find($subadmin_id);

    if ($subadmin) {
        // Delete the subadmin
        $subadmin->delete();

        // Return a successful response
        // return response()->json([
        //     'status' => true,
        //     'message' => $subadmin->name . " deleted successfully!"
        // ]);

        return redirect()
               ->route('admin.allSubadmins')   // <-- Make sure this is the correct route name for your subadmin list
               ->with('success', $subadmin->name . " deleted successfully!");


    } else {
        // Return a not found response if the subadmin does not exist
        // return response()->json([
        //     'status' => false,
        //     'message' => 'Subadmin not found',
        // ], 404);

        return redirect()
               ->route('admin.allSubadmins')   // <-- Make sure this is the correct route name for your subadmin list
               ->with('success', "Subadmin not found!");

    }
}


    public function showSubadminList(){
        $subadmins = Subadmin::all()->toArray();
        return view('admin.subadmin.index', compact('subadmins'));
    }

    public function showProfile(){
        $crntAdminID = Auth::guard('admin')->user()->id;
        $admin = Admin::find($crntAdminID);
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
{
    // For example, the currently logged-in admin
    $admin = Auth::guard('admin')->user(); 
    
    
    // Validate form data (adjust rules as needed)
    $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:admin,email,' . $admin->id,
        'username'  => 'required|string|max:50|unique:admin,username,' . $admin->id,
        'password'  => 'nullable|min:6|confirmed',
        'profile'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // If you're allowing images
        'status'    => 'nullable|in:0,1'
    ]);
    // Handle new profile image if uploaded
    if ($request->hasFile('profile')) {
        // Optionally delete old file if you store paths
        if ($admin->profile && Storage::exists('admin/profile/'.$admin->profile)) {
            Storage::delete('admin/profile/'.$admin->profile);
        }

        // Store the new file (e.g. in subadmin/profile folder)
        // store() automatically generates a unique filename
        $path = $request->file('profile')->store('admin/profile', 'public');
    } else {
        // Keep the old path if no new file uploaded
        $path = $admin->profile;
    }

    $admin->name = $request->input('name');
    $admin->email = $request->input('email');
    $admin->username = $request->input('username');

    // Only update password if provided
    if ($request->filled('password')) {
        $admin->password = Hash::make($request->input('password'));
    }

    $admin->profile = $path; // store the new or old path
    $admin->status = $request->input('status');
    
    $admin->save();

    // Redirect back with a success message
    return redirect()
        ->route('admin.myprofile')  // or wherever your profile route is
        ->with('success', 'Profile updated successfully!');
}


///FOR CSP AGENTS///////////////

    public function showCSPList(){
    $csps = User::all()->toArray();
    return view('admin.csp.index', compact('csps'));
    }

    // Show registration form
    public function showRegisterFormCSP()
    {
        return view('admin.csp.register');
    }

    // Handle registration
    public function registerCSP(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',

        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.csps')->with('success', 'CSP Agent created successful!');
    }

    public function deleteCSP(Request $request)
    {
        // Get the csp id from the request
        $csp_id = $request->input('id');
        // Find the csp by the id
        $csp = User::find($csp_id);
    
        if ($csp) {
            // Delete the csp
            $csp->delete();
    
            return redirect()
                   ->route('admin.csps')
                   ->with('success', $csp->name . " deleted successfully!");
        } else {
            return redirect()
                   ->route('admin.csps')
                   ->with('success', "CSP Agent not found!");
    
        }
    }

    public function editCSP($id)
    {
        $csp = User::find($id);
        // Pass the $subadmin variable to the view
        return view('admin.csp.editForm', compact('csp'));
    }

    public function updateCSP(Request $request)
    {
        $id = $request->id;
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6', 
            'profile'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // If you're allowing images
            'status'    => 'nullable|in:0,1'
        ]);
    
        // Find the subadmin by ID
        $csp = User::findOrFail($id);
    
        // Handle profile image if exists
        if ($request->hasFile('profile')) {
            // Store the file and get the paths
            $profile = $request->file('profile')->storeAs('csp/profile',$request->username.'_'.time().'.'.$request->file('profile')->getClientOriginalExtension(), 'public');

            //$path = $request->file('profile')->store('csp/profile', 'public');
            //print_r($profile);exit;
        } else {
            // If no file uploaded, retain the old profile image
            $profile = $csp->profile;
        }
    
        // Update the subadmin's data
        $csp->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $csp->password, // Update password only if provided
            'status' => ($request->status)?1:0,
            'profile' => $profile,
        ]);
    
        // Return a success response
        // return response()->json([
        //     'message' => 'Subadmin updated successfully.',
        //     'data' => $subadmin
        // ], 200);

        // Redirect to the subadmins index page with a success message
        return redirect()
               ->route('admin.csps')
               ->with('success', $csp->name. ' updated successfully.');

    }



    ////////////// MANAGE DOCUMENTS VERIFICATION///////////////
    ///////////////////////////////////////////////////////////

    /**
 * Get documents for a specific CSP
 * 
 * @param int $id The CSP ID
 * @return \Illuminate\Http\JsonResponse
 */
public function getCspDocuments($id)
{
    // Find the CSP
    $csp = User::findOrFail($id);
    
    // Get all documents for this CSP's KO code
    $documents = Documents::where('ko_code', $csp->ko_code)->get();
    
    // Format data for easier consumption in the frontend
    $formattedData = [];
    
    foreach ($documents as $document) {
        $formattedData[$document->certificate_id] = [
            'id' => $document->id,
            'certificate_name' => $document->certificate_name,
            'status' => $document->status,
            'verified' => $document->verified,
            'message' => $document->message
        ];
    }
    
    return response()->json($formattedData);
}

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

/**
 * Upload BC Agreement document
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function uploadAgreement(Request $request)
{
    // Validate the request
    $request->validate([
        'csp_id' => 'required|exists:users,id',
        'agreement_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'agreement_date' => 'required|date'
    ]);
    
    $cspId = $request->input('csp_id');
    $csp = User::findOrFail($cspId);
    $koCode = $csp->ko_code;
    
    // Store the agreement file
    $filename = $koCode . '_agreement.' . $request->file('agreement_file')->extension();
    $path = $request->file('agreement_file')->storeAs('agreements', $filename, 'public');
    
    // Update or create document record
    $document = Documents::updateOrCreate(
        [
            'ko_code' => $koCode,
            'certificate_id' => 3 // BC Agreement
        ],
        [
            'certificate_name' => 'BC Agreement',
            'status' => 'pending', // Set initial status as pending
            'verified' => 0,
            'message' => 'Agreement uploaded on ' . date('Y-m-d H:i:s') . '. Agreement date: ' . $request->input('agreement_date')
        ]
    );
    
    // Store the agreement path and date in a separate agreements table if needed
    // This is if you have a separate table for agreement details
    if (Schema::hasTable('agreements')) {
        DB::table('agreements')->updateOrInsert(
            ['ko_code' => $koCode],
            [
                'file_path' => $path,
                'agreement_date' => $request->input('agreement_date'),
                'updated_at' => now()
            ]
        );
    }
    
    return redirect()->back()->with('success', 'Agreement uploaded successfully');
}

/**
 * View certificate document
 * 
 * @param int $cspId The CSP ID
 * @param int $certificateId The certificate ID
 * @return \Illuminate\Http\Response
 */
public function viewCertificate($cspId, $certificateId)
{
    // Find the CSP
    $csp = User::findOrFail($cspId);
    $koCode = $csp->ko_code;
    
    // Logic to retrieve the certificate file path
    // This is placeholder logic - adjust based on your file storage system
    $certificateTypes = [
        1 => 'idcard',
        2 => 'bccertificate'
    ];
    
    $type = $certificateTypes[$certificateId] ?? 'unknown';
    
    // Look for certificate file in storage
    $path = "certificates/{$koCode}_{$type}.pdf";
    
    if (Storage::disk('public')->exists($path)) {
        return response()->file(storage_path('app/public/' . $path));
    }
    
    // If file not found, return a 404 or redirect
    return abort(404, 'Certificate file not found');
}

/**
 * View agreement document
 * 
 * @param int $cspId The CSP ID
 * @return \Illuminate\Http\Response
 */
public function viewAgreement($cspId)
{
    // Find the CSP
    $csp = User::findOrFail($cspId);
    $koCode = $csp->ko_code;
    
    // Check if we have a separate agreements table
    if (Schema::hasTable('agreements')) {
        $agreement = DB::table('agreements')->where('ko_code', $koCode)->first();
        
        if ($agreement && $agreement->file_path) {
            return response()->file(storage_path('app/public/' . $agreement->file_path));
        }
    }
    
    // If not in separate table, look in public storage
    $possibleExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
    
    foreach ($possibleExtensions as $ext) {
        $path = "agreements/{$koCode}_agreement.{$ext}";
        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }
    }
    
    // If file not found, return a 404 or redirect
    return abort(404, 'Agreement file not found');
}


}

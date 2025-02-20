<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Subadmin;

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
        ]);
        
        // Handle profile image if exists
        if ($request->hasFile('profile')) {
            // Store the file and get the paths
            $profile = $request->file('profile')->storeAs('subadmin/profile',$request->username.'_'.time().'.'.$request->file('profile')->getClientOriginalExtension());
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
            'password' => 'nullable|min:6|confirmed', 
        ]);
    
        // Find the subadmin by ID
        $subadmin = Subadmin::findOrFail($id);
    
        // Handle profile image if exists
        if ($request->hasFile('profile')) {
            // Store the file and get the paths
            $profile = $request->file('profile')->storeAs('subadmin/profile',$request->username.'_'.time().'.'.$request->file('profile')->getClientOriginalExtension());
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
        'status'    => 'required|in:0,1'
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

}

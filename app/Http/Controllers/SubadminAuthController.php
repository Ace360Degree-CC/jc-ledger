<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SubAdmin;
use App\Models\User;

class SubadminAuthController extends Controller
{
    public function showLoginForm(){
        return view('subadmin.auth.login');
    }

    public function login(Request $request){
        if(Auth::guard('subadmin')->attempt($request->only('email', 'password'))){
            return redirect()->route('subadmin.dashboard');
        }
 
        return back()->withErrors(['email' => 'Invalid Credentials']);

    }


    // Show registration form
    public function showRegisterForm()
    {
        return view('subadmin.auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:25|unique:subadmin,username',
            'email' => 'required|email|unique:subadmin,email',
            'password' => 'required|min:6|confirmed',
        ]);
        //print_r($request->name);exit;
        subadmin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1,
        ]);

        return redirect()->route('subadmin.login')->with('success', 'Registration successful! Please login.');
    }



    
    ///FOR CSP AGENTS///////////////

    public function showCSPList(){
        $csps = User::all()->toArray();
        return view('subadmin.csp.index', compact('csps'));
        }
    
        // Show registration form
        public function showRegisterFormCSP()
        {
            return view('subadmin.csp.register');
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
    
            return redirect()->route('subadmin.csps')->with('success', 'CSP Agent created successful!');
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
                       ->route('subadmin.csps')
                       ->with('success', $csp->name . " deleted successfully!");
            } else {
                return redirect()
                       ->route('subadmin.csps')
                       ->with('success', "CSP Agent not found!");
        
            }
        }
    
        public function editCSP($id)
        {
            $csp = User::find($id);
            // Pass the $subadmin variable to the view
            return view('subadmin.csp.editForm', compact('csp'));
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
                   ->route('subadmin.csps')
                   ->with('success', $csp->name. ' updated successfully.');
    
        }




    // Show dashboard
    public function dashboard()
    {
        return view('subadmin.dashboard');
    }

    // Handle logout
    public function logout()
    {
        Auth::guard('subadmin')->logout();
        return redirect()->route('subadmin.login');
    }



}

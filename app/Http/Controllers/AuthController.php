<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{

    public function showProfile()
{
    $crntUserID = Auth::guard('web')->user()->id;
    $myProfile = User::findOrFail($crntUserID);
    //print_r($myProfile['profile']);exit;
    return view('auth.profile', compact('myProfile'));
}

public function updateProfile(Request $request)
{
    $user = Auth::guard('web')->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
        'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // Update name & email
    $user->name = $request->name;
    $user->email = $request->email;

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Update profile image if uploaded
    if ($request->hasFile('profile')) {
        // Delete old image if exists
        if ($user->profile) {
            Storage::disk('public')->delete('csp/profile/' . $user->profile);
        }

        // Store new image
        $image = $request->file('profile');
        $imageName = time() . '.' . $image->extension();
        $image->storeAs('csp/profile', $imageName, 'public');


        // Save the image name in the database
        $user->profile = $imageName;
    }

    //Update signature if provided
    if($request->hasFile('signature')){
        //Delete old signature if exists
        if($user->signature){
            Storage::disk('public')->delete('csp/signature/' . $user->signature);
        }

        //Store new signature
        $signature = $request->file('signature');
        $signatureName = time().'_'.$user->ko_code.'.'.$signature->extension();
        $signature->storeAs('csp/signature', $signatureName, 'public');

        //Save the signature in the database
        $user->signature = $signatureName;
    }

    $user->save();

    return redirect()->route('auth.profile')->with('success', 'Profile updated successfully!');
}


    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
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
            'status'=>0
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Show dashboard
    public function dashboard()
    {
        return view('dashboard');
    }

    // Handle logout
    public function logout()
    {
        //print_r(Auth::user()->id);exit;
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}

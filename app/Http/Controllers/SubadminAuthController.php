<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SubAdmin;

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
            'username' => 'required|string|max:10|unique:subadmin,username',
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

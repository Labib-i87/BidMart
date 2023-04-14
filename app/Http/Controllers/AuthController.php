<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function registration()
    {
        return view('auth.registration');
    }
    public function registerUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:4|max:12'
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $res = $user->save();
        if ($res) {
            return back()->with('success', 'You have registered successfully.');
        } else {
            return back()->with('fail', 'Something went wrong.');
        }
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:4|max:12'
        ]);

        $user = User::where('username', '=', $request->username)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('loginID', $user->id);
                return redirect('homepage');
            } else {
                return back()->with('fail', 'Incorrect Password.');
            }

        } else {
            return back()->with('fail', 'Invalid Username.');
        }

    }



    public function logout()
    {
        if (Session::has('loginID')) {
            session()->pull('loginID');
            return redirect('login');
        }
    }

}
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use Auth;

use App\User;

class UserController extends Controller
{
    public function displayLogin()
    {
        $error_message = Session::get('error_message');
        $user_logged_in = Auth::user();
        if (!$user_logged_in)
        {
            return view('login', [
                'error_message' => $error_message,
                'currentUser' => $user_logged_in
            ]);
        }
        else
        {
            return redirect('/');   
        }
    }
    
    public function authorize(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];
        $remember_me = $request->remember == 'on' ? true: false;
        if (Auth::attempt($credentials, $remember_me))
        {
            return redirect()->intended('/');
        }
        else
        {
            return redirect('/login')->with('error_message', 'Invalid username or password');
        }
    }
    
    public function createUser(Request $request)
    {
        $validation = User::validate($request->all());
        if ($validation->passes())
        {
            $user = new User();
            $user->first_name = $request->input('firstname');
            $user->last_name = $request->input('lastname');
            $user->username = $request->input('username');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            
            return redirect('/')->with('success_message', 'Successfully Signed Up');
        }
        else
        {
            return redirect('/')->withInput()->withErrors($validation->errors());   
        }
    }
    
    public function index()
    {
        $success_message = Session::get('success_message');
        $user_logged_in = Auth::user();
        return view('index', [
            'success_message' => $success_message,
            'currentUser' => $user_logged_in
        ]);   
    }
    
    public function home()
    {
        return redirect('/');   
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }
}
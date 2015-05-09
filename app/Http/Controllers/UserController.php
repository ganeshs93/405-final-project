<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\InstagramAPI;
use App\Models\Businesses;
use App\Models\Suggestions;
use Hash;
use Session;
use Auth;

use App\User;

class UserController extends Controller
{
    public function displayLogin()
    {
        $error_message = Session::get('error_message');
        $success_message = Session::get('success_message');
        $user_logged_in = Auth::user();
        if (!$user_logged_in)
        {
            return view('login', [
                'success_message' => $success_message,
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
        $error_message = Session::get('error_message');
        $user_logged_in = Auth::user();
        if ($user_logged_in and $user_logged_in->access_level == 1)
        {
            $suggestions = Suggestions::all();    
        }
        else
        {
            $suggestions = null;
        }
        return view('index', [
            'success_message' => $success_message,
            'error_message' => $error_message,
            'currentUser' => $user_logged_in,
            'suggestions' => $suggestions
        ]);   
    }
    
    public function removeSuggestion($business_id, $instagram_username)
    {
        $suggestions = Suggestions::where('business_id', '=', $business_id)
                                ->where('instagram_username', '=', $instagram_username)
                                ->get();
        foreach ($suggestions as $suggestion)
        {
            $suggestion->delete();    
        }
        return redirect('/')->with('success_message', 'Suggestion Removed');
    }
    
    public function addSuggestion($business_id, $instagram_username)
    {
        $data = $this->getInstagramID($instagram_username);
        if (count($data->data) == 0)
        {
            return redirect('/')->with('error_message', 'Cannot find Instagram Page');
        }
        $id = $data->data[0]->id;
        $check_business = Businesses::where('id', '=', $business_id)->first();
        if ($check_business)
        {
            $check_business->instagram_id = $id;
            $check_business->save();
        }
        else
        {
            $business = new Businesses();
            $business->id = $business_id;
            $business->instagram_id = $id;
            $business->save();
        }
        $suggestions = Suggestions::where('business_id', '=', $business_id)
                                ->where('instagram_username', '=', $instagram_username)
                                ->get();
        foreach ($suggestions as $suggestion)
        {
            $suggestion->delete();    
        }
        return redirect('/')->with('success_message', 'Username set');
    }
    
    public function getInstagramID($username)
    {
        $api = new InstagramAPI();
        $url = $api->buildUserSearchURL($username);
        return $api->getJSON($url);
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
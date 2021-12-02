<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\BoeFrsController;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

	use AuthenticatesUsers;

	/**
	* Where to redirect users after login.
	*
	* @var string
	*/
	protected $redirectTo = '/home';

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct() {
		$this->middleware('guest')->except('logout');
	}
/*
	public function login(Request $request) {
		$this->validate($request, [
			'email' => 'required|max:255|email',
			'password' => 'required|confirmed',
		]);
		if (Auth::attempt(['email' => $email, 'password' => $password])) {
			return redirect()->intended('/home');
		} else {
			return redirect()->back();
		}
	}
*/
	public function logout(Request $request) {
		Auth::logout();
		Session::flush();
		return redirect('/init');
	}
}

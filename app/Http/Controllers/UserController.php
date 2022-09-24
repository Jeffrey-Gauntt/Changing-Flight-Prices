<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // show register form
	public function create() {
		return view('register');
	}

	// process and store new user
	public function store(Request $request) {
		$formFields = $request->validate([
            'name' => ['required', 'min:3', 'unique:users,name'],
            'password' => 'required|confirmed|min:4'
        ]);

		// Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        $user = User::create($formFields);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
	}

	// Logout User
    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');

    }

	// show login form
	public function login() {
		return view('login');
	}

	// Authenticate User
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'name' => 'required|min:3',
            'password' => 'required|min:4'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['name' => 'Invalid Username and/or Password'])->onlyInput('name');
    }
}

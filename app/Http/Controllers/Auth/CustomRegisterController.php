<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FranchiseOwner;
use Illuminate\Validation\ValidationException;

class CustomRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0, // Set default or modify based on your logic
        ]);

        Auth::login($user);

        if (!$user->isAdmin() && !FranchiseOwner::where('email', $user->email)->exists()) {
            Auth::logout(); // Log out the user
            return redirect('/home')->with('status', 'Registration successful, but you do not have access.');
        }

        return redirect('/home'); // Redirect to home or some other page
    }
}

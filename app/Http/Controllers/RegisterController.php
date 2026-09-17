<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Поле имя обязательно для заполнения',
            'email.required' => 'Поле email обязательно для заполнения',
            'password.required' => 'Поле пароль обязательно для заполнения',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return response()->json([
            'user' => $user,
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mail' => 'required|string|email',
            'password' => 'required|min:6',
        ], [
            'mail.required' => 'Поле Логин обязательно для заполнения',
            'mail.email' => 'Некорректный формат email',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
        ]);

        $user = User::where('email', $validated['mail'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'errors' => [
                    'mail' => ['Неверный логин или пароль'],
                ],
            ], 422);
        }

        Auth::login($user);

        return response()->json([
            'user' => $user,
        ], 200);
    }
}

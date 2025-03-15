<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'password' => 'required|string|min:6',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil, silakan login.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'user' => $user
            ]);
        }

        // Jika login gagal
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);
    }

}

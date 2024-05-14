<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
        } else {
            Auth::login($user);
            $request->user()->tokens()->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    // public function checkLogin(Request $request)
    // {
    //     $request->validate([
    //         'device_name' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
    //     } else {
    //         Auth::login($user);
    //         $request->user()->tokens()->delete();
    //         $token = $user->createToken($request->email)->plainTextToken;
    //         return response()->json([
    //             'user' => [
    //                 'id' => $user->id,
    //                 'name' => $user->name,
    //                 'email' => $user->email,
    //             ],
    //             'token' => $token
    //         ], 200);
    //     }
    // }

    public function addToUser(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|max:255',
                'email' => 'required|email:dns|unique:users',
                'password' => 'required|min:8|max:255',
                'phone' => 'required',
                'address' => 'required',
            ]
        );
        // $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);
        // $request->session()->flash('success', 'Registration successfull! Please Login!');
        return response()->json([
            'user' => $user,
            'message' => 'Berhasil Membuat akun!'
        ], 200);
    }
}

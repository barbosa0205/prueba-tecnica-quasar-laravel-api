<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('my-token')->plainTextToken;


        $user->save();

        return response()->json([
            'message' => 'user register successfull',
            'token' => $token
        ], 200);
    }

    public function signin(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "errors" => [
                    "email" => [
                        "Wrong credentials."
                    ],
                ]
            ], 422);
        }

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json([
            'message' => 'user login successfull',
            'token' => $token
        ], 200);
    }
}

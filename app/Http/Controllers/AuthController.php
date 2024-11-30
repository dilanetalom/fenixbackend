<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'gender' => 'required|string|max:255',
        'role' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('authToken')->accessToken;

    return response()->json(['token' => $token], 200);
}


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token, 'user'=>$user], 200);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}


public function allusers()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Retourner les utilisateurs en JSON
        return response()->json($users);
    }

    
public function logout(Request $request)
{
    $request->user()->token()->revoke();
    return response()->json(['message' => 'Successfully logged out'], 200);
}

}

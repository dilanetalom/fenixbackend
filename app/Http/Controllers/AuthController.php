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



public function update(Request $request, $userId)
{
    $user = User::findOrFail($userId);

    // Update user data
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'role' => $request->role,
    ]);

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
        $user->save();
    }

    return response()->json(['message' => 'User updated successfully'], 200);
}





public function allusers()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Retourner les utilisateurs en JSON
        return response()->json($users);
    }



public function deleteuser(string $id)
    {
        try {

            // Trouver le livre par son ID
            $user = User::findOrFail($id);
            // Supprimer le livre
            $user->delete();
    
            return response()->json([
                'message' => 'utilisateur supprimé avec succès.',
            ]);


        } catch (Exception $e) {
            return response()->json([
                'error' => 'utilisateur non trouvé ou erreur lors de la suppression.',
            ], 404);
        }
    }

    
public function logout(Request $request)
{
    $request->user()->token()->revoke();
    return response()->json(['message' => 'Successfully logged out'], 200);
}


public function authUser()
{
    $user = Auth::user(); // ou auth()->user();
    
    return response()->json($user);
}


}

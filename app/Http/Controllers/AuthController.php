<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'=> $data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);

        // Assign role (default to student if not specified)
        $role = $data['role'] ?? 'student';
        $user->assignRole($role);

        $token = $user->createToken('react_app')->plainTextToken;

        // Load roles and permissions for response
        $user->load('roles', 'permissions');

        return response()->json(['user'=>$user,'token'=>$token],201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email',$data['email'])->first();
        if (!$user || !Hash::check($data['password'],$user->password)) {
            return response()->json(['message'=>'Invalid credentials'],401);
        }

        $token = $user->createToken('react_app')->plainTextToken;

        // Load roles and permissions for response
        $user->load('roles', 'permissions');

        return response()->json(['user'=>$user,'token'=>$token],200);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles', 'permissions');
        return response()->json($user,200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message'=>'Logged out'],200);
    }
}

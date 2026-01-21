<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=> 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name'=> $data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);
        $token =$user->createToken('react_app')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token],201);
        }
        public function login(Request $request){
            $data = $request->validate([
                'email'=>'required|string|email',
                'password'=>'required|string',
            ]);
            $user = User::where('email',$data['email'])->first();
            if(! $user || !Hash::check($data['password'],$user->password)){
                return response()->json(['message'=>'Invalid credentials'],401);
            }
            $token = $user->createToken('react_app')->plainTextToken;
            return response()->json(['user'=>$user,'token'=>$token],200);
        }
        public function me(Request $request){
            return response()->json($request->user(),200);
        }
        public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message'=>'Logged out'],200);
        }
}

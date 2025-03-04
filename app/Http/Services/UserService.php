<?php

namespace App\Http\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Models\PasswordReset;

class UserService
{
    public function login(array $data)
    {
        if (!$token = JWTAuth::attempt($data)) {
            return response()->json(null, 401);
        }

        JWTAuth::setToken($token);

        return response()->json(["token" => $token, "user" => JWTAuth::user()], 200)
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('Authorization', "Bearer $token");
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(null, 200)
            ->header('Access-Control-Expose-Headers', '')
            ->header('Authorization', '');
    }

    public function refreshToken()
    {
        if (!$token = JWTAuth::refresh(JWTAuth::getToken())) {
            return response()->json(null, 401);
        }

        JWTAuth::setToken($token);
        
        return response()->json(compact('token'), 200)
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('Authorization', "Bearer $token");
    }

    public function createUser(array $data)
    {
        Mail::raw('Your account ('.$data['email'].') has been successfully created!', function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject('Account Created');
        });

        return User::create($data+['password' => Hash::make(Str::random(25))]);
    }

    public function sendResetPasswordEmail(array $data)
    {
        try{

            $user = User::where('email', $data['email'])->first();
            if(!$user){
                return response()->json(null, 404);
            }

            $token = Str::random(6);
            $data['token'] = $token;
            
            PasswordReset::where('email', $data['email'])->delete();
            PasswordReset::create([
                'email' => $data['email'],
                'token' => $token,
            ]);

            Mail::raw('Your reset password token is '.$token, function ($message) use ($data) {
                $message->to($data['email'])
                        ->subject('Reset Password');
            });

            return response()->json(null, 200);
        }catch(\Exception $e){
            return response()->json(null, 400);
        }
    }

    public function resetPassword(array $data)
    {
        // Find password reset record
        $passwordReset = PasswordReset::where('email', $data['email'])
            ->where('token', $data['token'])
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$passwordReset) {
            return response()->json(null, 498);
        }

        // Update user password
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            return response()->json(null, 401);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        // Delete the used token
        $passwordReset->delete();

        return response()->json(null, 200);
    }

    public function editUser(array $data)
    {
        $user = User::find($data['id']);

        if(!$user){
            return response()->json(null, 404);
        }

        if (array_key_exists('name', $data)) {
            $user->name = $data['name'];
        }
    
        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }

        $user->save();

        return response()->json($user, 200);
    }

    public function deleteUser(array $data)
    {
        $user = User::find($data['id']);
        $user->delete();

        return response()->json(null, 200);
    }

    public function getAllUsers()
    {
        return response()->json(User::all(), 200);
    }
    
}
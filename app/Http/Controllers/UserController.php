<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;

use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getAppInfo()
    {
        return $this->userService->getAppInfo();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        return $this->userService->login($request->only('email','password'));
    }

    public function logout(){
        return $this->userService->logout();
    }

    public function refreshToken(){
        return $this->userService->refreshToken();
    }

    public function createUser(Request $request){
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
        ]);
        return $this->userService->createUser($request->only('email', 'name'));
    }

    public function sendResetPasswordEmail(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        return $this->userService->sendResetPasswordEmail($request->only('email'));
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        return $this->userService->resetPassword($request->only('email', 'token', 'password'));
    }

    public function editUser(Request $request, $id){
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
        ]);
        return $this->userService->editUser($request->only(['name', 'email']) + ['id' => $id]);
    }

    public function editMe(Request $request){
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
        ]);
        return $this->userService->editUser($request->only(['name', 'email']) + ['id' => JWTAuth::user()->id]);
    }

    public function deleteUser(Request $request, $id){
        return $this->userService->deleteUser($request->only('id') + ['id' => $id]);
    }

    public function getAllUsers(){
        return $this->userService->getAllUsers();
    }

}

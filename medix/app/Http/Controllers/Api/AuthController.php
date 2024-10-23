<?php

namespace App\Http\Controllers\Api;

use App\Custom\Services\EmailVerificationServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(private EmailVerificationServices $service){

    }

    public function login(AuthRequest $request){
        $loginData = $request->validated();

        $user = User::where("email", $loginData["email"])->first();

        if(!$user){
            throw ValidationException::withMessages([
                "error"=> ["the provided credentials are incorrect"]
            ]);
        }

        $passwordVerify = Hash::check($loginData["password"], $user->password);

        if(!$passwordVerify){
            throw ValidationException::withMessages([
                "error"=> ["the provided credentials are incorrect"]
            ]);
        }
        $token = $user->createToken("api-Token")->plainTextToken;
        return response()->json([
            "token"=>$token,
        ]);
    }

    public function logout(){
        request()->user()->tokens()->delete();

        return response()->json([
            "message"=>"logout successful"
        ]);
    }

    public function register(RegisterRequest $request){
        $registerData = $request->validated();

        $userCheck = User::where("email", $registerData["email"])->first();
        if($userCheck){
            throw ValidationException::withMessages([
                "message"=> "User already exists"
            ]);
        }
        $registerData["password"] = hash::make($registerData["password"] );

        $newUser = User::create($registerData);
        
        if($newUser){
            $this->service->sendVerificationLink($newUser);

            return response()->json([
                "message"=>"registration successful",
                "status"=>"success"
            ]);
        }else{
            return response()->json([
                "message"=>"Error occurred. Please try again",
                "status"=>"failed"
            ], 500);
        }
    }

public function verifyEmail(VerifyEmailRequest $request){
    $email = $request->email;
    $token = $request->token;
    return $this->service->verifyToken($email, $token);
}
}

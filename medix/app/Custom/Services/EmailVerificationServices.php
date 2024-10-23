<?php

namespace App\Custom\Services;

use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Models\User;

Class EmailVerificationServices{

    public function sendVerificationLink(object $user):void{

        Notification::send($user, new EmailVerificationNotification($this->generateEmailVerifyLink($user->email)));
    
    }

    public function verifyToken(string $email, string $token){
        $checkToken = EmailVerificationToken::where("email", $email)
        ->where("token", $token)->first();

        if($checkToken){
            if($checkToken->expired_at <= now()->addMinutes(60)){
                $user = User::where("email", $email)->first();
                if($user && $user->email_verified_at === null){
                    $user->markEmailAsVerified();

                    $checkToken->delete();

                    return response()->json([
                        "message"=>"Verification Completed",
                        "status"=>"success"
                    ]);
                }else{
                    return response()->json([
                        "message"=>"User is already verified",
                        "status"=>"failed"
                    ]);
                }
            }else{
                return response()->json([
                    "message"=>"token is expired",
                    "status"=>"failed"
                ]);
            }
        }else{
            return response()->json([
                "message"=>"Invalid Token",
                "status"=>"failed"
            ]);
        }
        // }elseif($checkToken->expired_at >= now()->addMinutes(60)){
        //     $user = User::where("email", $email)->first();

        //     if($user->email_verified_at === null){
        //         $user->markEmailAsVerified();

        //         $checkToken->delete();

        //         return response()->json([
        //             "message"=>"Email verirified Successfully",
        //             "status"=>"success"
        //         ], 200);
        //     }else{
        //         return response()->json([
        //             "message"=>"email already verified"
        //         ]);
        //     }
        // }

    }

    public function generateEmailVerifyLink(string $email){
        $checkEmailExists = EmailVerificationToken::where("email", $email)->first();

        if($checkEmailExists) $checkEmailExists->delete();

        $token = Str::uuid();
        $url = config("app.url")."?token=".$token."&email=".$email;

        $saveToken = EmailVerificationToken::create([
            "token"=>$token,
            "email"=>$email,
            "expired_at"=>now()->addMinutes(60)
        ]);

        if($saveToken){
            return $url;
        }

    }
}
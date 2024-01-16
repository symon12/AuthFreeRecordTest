<?php

namespace App\Http\Controllers;

use App\Helper\jwt_token;
use App\Models\User;
use Illuminate\Http\Request;
use Faker\Extension\Extension;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function registration(Request $request){
        try {
            $this->validate($request, [
                "name"=>"required|string|max:200",
                "email"=>"required|string|max:200",
                "mobile"=>"required|string|max:200",
                "password"=>"required|string|min:4"
            ]);
             User::create([
                "name"=>$request->input("name"),
                "email"=>$request->input("email"),
                "mobile"=>$request->input("mobile"),
                "password"=>$request->input("password")
                
               
            ]);
            return response()->json([
                'status'=>'success',
                 'message'=>'registration successful'
            ],201);

        } catch (\Exception $e) {
       return response()->json([
       'status'=> 'failed',
    //    'message'=>'user registration failed'
       'message'=>$e->getMessage()
       ],200);
    

        }   
    }

    public function login(Request $request){
       
        $count=User::where('email','=',$request->input('email'))
        ->orWhere('password','=',$request->input('password'))
        ->count();
        if($count==1){
            $token=jwt_token::create($request->input('email'));
            return response()->json([
                'status'=>'success',
                 'message'=>'Login successful',
                 'token'=>$token
            ],201);

        }else{
            return response()->json([
                'status' => 'failed',
                // 'message' => $e->getMessage(),
                'message' => 'unauthorized',
            ], 200);
        }
}
public function sendOpt(Request $request){
    $email = $request->input('email');
    $otp =rand(1000,9999);
    $count = User::where('email','=',$email)->count();
    if($count== 1){ 
       Mail::to($email)->send( new OtpMail($otp));
       User::where('email','=',$email)->update(['otp'=> $otp]);
       return response()->json([
        'status'=> 'success', 
        'message'=> 'Your Email Account 4 digit Otp Send Successful ',
        ], 201);

}else{
    return response()->json([
        'status'=> 'failed', 
        'message'=> 'unauthorized ',
        ], 200);
}
}

public function verifyOtp(Request $request){
    $email = $request->input('email');
    $otp= $request->input('otp');
    $count = User::where('email','=',$email)->
    orWhere('otp','=',$otp )->count();

    if($count== 1){
        User::where('email','=',$email)->update(['otp'=> '0']);
        $token=jwt_token::createTokenForSet($request->input('email'));
        return response()->json([
            'status'=>'success',
             'message'=>'Otp  verification successful',
             'token'=>$token
        ],201);

    }else{
        return response()->json([
            'status'=> 'failed', 
            'message'=> 'unauthorized ',
            ], 200);
    }
}

public function resetPassword(Request $request){
    try{
$email= $request->header('email');
$password= $request->input('password');
User::where('email','=',$email)->update(['password'=>$password]);
return response()->json([
    'status'=> 'success',
    'message'=> 'Request success'
    ],201);
}catch(\Exception $e){
    return response()->json([
        'status'=> 'failed',
        // 'message'=> $e->getMessage(),
        'message'=> 'Something will wrong'
        ],200);


}
}
}
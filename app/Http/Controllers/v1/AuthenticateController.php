<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use TheSeer\Tokenizer\Token;

class AuthenticateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Function to validate user credentials
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $creds = $request->only(['email', 'password']);
        $token = Auth::attempt($creds);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid Password'
            ]);
        }

        return response()->json([
            'token' => $token,
            'type' => 'Bearer Token'
        ]);
    }

    /**
     * Function To register New User
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:10',
            'last_name' => 'required|string|max:10',
            'email' => 'required|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number|min:10|max:10',
            'address' => 'string',
            'password' => 'required|confirmed'
        ]);

        $user = User::firstOrNew(['email' => $request->email]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(
            ["message" => "User Created Successfully"],
            201
        );
    }

    /**
     * Function to send otp for forgot Password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(1000,9999);
        $userId = User::where('email',$request->email)->value('id');

        $resetPassword = new ResetPassword([
            'user_id' => $userId,
            'otp' => $otp
        ]);

        $resetPassword->save();

        if($status){
            return response()->json([
                "message" => "Otp Send Successfully"
            ]);
        }

    }

    /**
     * Function to update-password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        return true;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // إرسال الكود
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits_between:10,15',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $otpCode = rand(1000, 9999);

        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            ['otp_code' => $otpCode]
        );

        // إرسال الكود (في التطبيق الحقيقي ترسله SMS)
        return response()->json([
            'message' => 'OTP sent successfully.',
            'otp_code' => $otpCode  // في الواقع ابعته SMS
        ]);
    }

    // تأكيد الكود وإصدار التوكن
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'otp_code' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone)
            ->where('otp_code', $request->otp_code)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid OTP code.'], 401);
        }

        $user->update(['otp_code' => null]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }


//    public function completeProfile(Request $request)
//    {
//        $user = auth('api')->user();
//
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'nullable|email',
//            'date_of_birth' => 'nullable|date',
//        ]);
//
//        $user->update([
//            'name' => $request->name,
//            'email' => $request->email,
//            'gender' => $request->gender,
//            'date_of_birth' => $request->date_of_birth,
//            'updated_profile' => true,
//        ]);
//
//        return response()->json([
//            'message' => 'تم تحديث البيانات الشخصية.',
//            'user' => $user
//        ]);
//    }


    public function setPassword(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password set successfully'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'بيانات الدخول غير صحيحة.'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }


    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'updated_profile' => true
        ]);

        return response()->json([
            'message' => 'تم تحديث البيانات بنجاح.',
            'user' => $user
        ]);
    }

    public function me(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => $user
        ]);
    }


}

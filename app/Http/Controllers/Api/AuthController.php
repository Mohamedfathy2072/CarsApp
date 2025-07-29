<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // إرسال الكود
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits_between:10,15',
            'isForgotPass' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $existingUser = User::where('phone', $request->phone)->first();

        if (!$request->isForgotPass && $existingUser && $existingUser->password) {
            return response()->json([
                'message' => 'هذا الرقم مسجل بالفعل، الرجاء تسجيل الدخول.'
            ], 409);
        }

        if ($request->isForgotPass && (!$existingUser || !$existingUser->password)) {
            return response()->json([
                'message' => 'لا يوجد حساب مرتبط بهذا الرقم.'
            ], 404);
        }

        $otpCode = rand(1000, 9999);

        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            ['otp_code' => $otpCode]
        );

        return response()->json([
            'message' => 'OTP sent successfully.',
            'otp_code' => $otpCode,
            'user_id' => $user->id
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

        $user->update([
            'otp_code' => null,
            'is_verified' => true
        ]);

        return response()->json([
            'message' => 'تم التحقق من الكود بنجاح. الرجاء تعيين كلمة المرور لإكمال التسجيل.',
            'user_id' => $user->id
        ]);
    }





    public function completeRegistration(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->user_id);

        if (!$user->is_verified) {
            return response()->json(['error' => 'يرجى التحقق من رقم الهاتف أولًا.'], 401);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'password' => Hash::make($request->password),
            'updated_profile' => true,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'تم إكمال التسجيل بنجاح.',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !$user->password) {
            return response()->json(['error' => 'الرجاء إكمال التسجيل أولًا.'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'بيانات الدخول غير صحيحة.'], 401);
        }

        $token = JWTAuth::fromUser($user);

        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);
        return response()->json([
            'message' => 'تم تسجيل الدخول إلى حسابك بنجاح.',
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
            'date_of_birth' => 'nullable|date',
            'fcm_token' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'updated_profile' => true,
            'fcm_token' => $request->fcm_token,
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

    public function updatePassword(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old password is incorrect.'
            ], 403);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.'
        ]);
    }


    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح.'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'فشل في تسجيل الخروج. حاول مرة أخرى.'
            ], 500);
        }
    }
    public function deleteAccount(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized'
                ], 401);
            }

            // حذف المستخدم
            $user->delete();

            // إبطال التوكن
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'تم حذف الحساب بنجاح.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء حذف الحساب. حاول مرة أخرى.'
            ], 500);
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::find($request->user_id);

        $user->update([
            'password' => Hash::make($request->password)
        ]);


        return response()->json([
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح.'
        ]);
    }



}

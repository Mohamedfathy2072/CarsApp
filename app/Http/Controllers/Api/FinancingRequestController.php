<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarInstallmentCalculator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinancingRequest;
use App\Models\FinancingRequest;
use Illuminate\Http\Request;

class FinancingRequestController extends Controller
{

    public function store(StoreFinancingRequest $request)
    {
        $user = auth()->user();

        // 1. حساب عدد الطلبات "In process" للمستخدم الحالي
        $inProcessCount = FinancingRequest::where('user_id', $user->id)
            ->where('status', 'In process')
            ->count();

        // 2. لو عدد الطلبات >= 3 نرجع can_apply = false
        if ($inProcessCount >= 3) {
            return response()->json([
                'can_apply' => false,
                'message' => 'You cannot apply for financing more than 3 times while in process.'
            ], 200);
        }

        // 3. تجهيز البيانات للحفظ
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status'] = 'In process'; // نضيف الحالة هنا

        $data['card_front'] = $request->file('card_front')->store('cards', 'public');
        $data['card_back'] = $request->file('card_back')->store('cards', 'public');

        if ($request->hasFile('club_membership_card')) {
            $data['club_membership_card'] = $request->file('club_membership_card')->store('documents', 'public');
        }

        if ($request->hasFile('medical_insurance_card')) {
            $data['medical_insurance_card'] = $request->file('medical_insurance_card')->store('documents', 'public');
        }

        if ($request->hasFile('owned_car_license_front')) {
            $data['owned_car_license_front'] = $request->file('owned_car_license_front')->store('documents', 'public');
        }

        if ($request->hasFile('owned_car_license_back')) {
            $data['owned_car_license_back'] = $request->file('owned_car_license_back')->store('documents', 'public');
        }

        // 4. إنشاء الطلب
        $financing = FinancingRequest::create($data);

        return response()->json([
            'can_apply' => true,
            'data' => $financing,
            'message' => 'Financing request created successfully.'
        ], 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. جلب page و size من request أو استخدام قيم افتراضية
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        // 2. حساب عدد الطلبات "In process" كاملة للمستخدم
        $inProcessCount = FinancingRequest::where('user_id', $user->id)
            ->where('status', 'In process')
            ->count();

        // 3. جلب الطلبات مع العلاقات واستخدام paginate
        $requests = FinancingRequest::with('brand')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($size, ['*'], 'page', $page);

        // 4. تحويل النتائج بالتنسيق المطلوب
        $formatted = $requests->map(function ($item) {
            return [
                'id' => $item->id,
                'brand' => $item->car_brand,
                'brand_img' => $item->brand?->image_path,
                'car_model' => $item->car_model,
                'year' => $item->manufacture_year,
                'price' => $item->total_price,
                'status' => $item->status,
                'created_at' => $item->created_at->toDateString(),
            ];
        });

        // 5. إعداد pagination metadata
        $pagination = [
            'current_page' => $requests->currentPage(),
            'per_page' => $requests->perPage(),
            'total' => $requests->total(),
            'last_page' => $requests->lastPage(),
        ];

        // 6. الإرجاع النهائي
        return response()->json([
            'can_apply' => $inProcessCount < 3,
            'data' => $formatted,
            'pagination' => $pagination,
        ]);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:financing_requests,id',
        ]);

        $financing = FinancingRequest::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($financing->status !== 'In process') {
            return response()->json(['message' => 'Cannot cancel this request.'], 403);
        }

        $financing->update(['status' => 'Cancelled']);
        return response()->json(['message' => 'Request cancelled successfully.']);
    }


}

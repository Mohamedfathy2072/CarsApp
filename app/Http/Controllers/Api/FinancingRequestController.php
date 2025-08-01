<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarInstallmentCalculator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinancingRequest;
use App\Models\FinancingRequest;
use Illuminate\Http\Request;

class FinancingRequestController extends Controller
{

    public function store(Request $request)
    {
        $user = auth()->user();

        // تحقق من عدد الطلبات الجارية
        $inProcessCount = FinancingRequest::where('user_id', $user->id)
            ->where('status', 'In process')
            ->count();

        if ($inProcessCount >= 3) {
            return response()->json([
                'can_apply' => false,
                'message' => 'لا يمكنك تقديم طلب جديد حالياً. لديك 3 طلبات جارية.'
            ], 403); // أو 200 لو عايزها request ناجحة لكن بدون تنفيذ
        }

        // تحقق من البيانات المطلوبة
        $data = $request->validate([
            'car_brand' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'manufacture_year' => 'required|numeric',
            'total_price' => 'required|numeric',
            // باقي الحقول حسب الحاجة
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'In process'; // الحالة الابتدائية

        $requestCreated = FinancingRequest::create($data);

        return response()->json([
            'can_apply' => true,
            'message' => 'تم إنشاء الطلب بنجاح.',
            'data' => $requestCreated
        ], 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // جلب page و size من الريكوست
        $size = $request->input('size', 10); // default = 10
        $requests = FinancingRequest::with('brand')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($size); // Laravel يعمل pagination تلقائي

        // حساب عدد الطلبات اللي في حالة "In process"
        $inProcessCount = FinancingRequest::where('user_id', $user->id)
            ->where('status', 'In process')
            ->count();

        // هل يقدر يقدم؟
        $canApply = $inProcessCount < 3;

        // تعديل البيانات المرجعة
        $formattedData = $requests->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'brand' => $item->car_brand,
                'brand_img' => $item->brand?->image_path, // الصورة بالرابط
                'car_model' => $item->car_model,
                'year' => $item->manufacture_year,
                'price' => $item->total_price,
                'status' => $item->status,
                'created_at' => $item->created_at->toDateString(),
            ];
        });

        // استبدال البيانات داخل الـ paginator
        $requests->setCollection($formattedData);

        return response()->json([
            'can_apply' => $canApply,
            'data' => $requests->items(),
            'pagination' => [
                'current_page' => $requests->currentPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
                'last_page' => $requests->lastPage(),
            ],
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

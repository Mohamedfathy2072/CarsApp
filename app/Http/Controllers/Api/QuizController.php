<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Quiz;
use App\Services\CarService;
use Illuminate\Http\Request;

class QuizController extends BaseController
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        $questions = Quiz::all()->map(function ($q) {
//            if ($q->attribute === 'brand_id') {
//                $options = Brand::select('id as value', 'name as label')->get();
//            } else {
//                $options = collect($q->options)->map(function ($opt) {
//                    return [
//                        'value' => $opt,
//                        'label' => $opt,
//                    ];
//                });
//            }
//
//            return [
//                'attribute' => $q->attribute,
//                'question' => $q->question,
//                'options' => $options,
//            ];
//        });
//
//        return response()->json($questions);
//    }

    public function index()
{
    $quizzes = Quiz::all();

    $items = $quizzes->mapWithKeys(function ($quiz) {
        return [
            $quiz->id => [
                'id' => $quiz->id,
                'question' => $quiz->question,
                'type' => $quiz->type,
                'options' => $quiz->options, // تأكد إنها casted to array في الـ model
                'created_at' => $quiz->created_at,
                'updated_at' => $quiz->updated_at,
            ]
        ];
    });

    return response()->json([
        'status' => true,
        'message' => 'Quizzes fetched successfully.',
        'data' => [
            'items' => $items,
        ]
    ]);
}


    public function suggestCars(Request $request)
    {
        $query = Car::query();
        $size = $request->input('size', 10);

        if ($request->filled('body_type')) {
            $query->where('body_type', $request->body_type);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        if ($request->filled('km_driven_max')) {
            $query->where('km_driven', '<=', $request->km_driven_max);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('engine_cc_min') && $request->filled('engine_cc_max')) {
            $query->whereBetween('engine_cc', [$request->engine_cc_min, $request->engine_cc_max]);
        }

        if ($request->filled('year_min')) {
            $query->where('year', '>=', $request->year_min);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('license_validity')) {
            $query->where('license_validity', $request->license_validity);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        $cars = $query->with(['images', 'brand'])->paginate($size);

        $this->carService->formatCars($cars);

        return $this->successResponse($cars, "Cars fetched successfully.");
    }
}

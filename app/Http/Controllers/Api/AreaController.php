<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $areas = Area::where('governorate_id', $request->governorate_id)->get();
        return response()->json($areas);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $faculties = Faculty::where('university_id', $request->university_id)->get();

        return response()->json($faculties);
    }
}

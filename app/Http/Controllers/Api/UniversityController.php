<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function universitiesOnly()
    {
        return response()->json(University::select('id', 'name')->get());
    }

    public function facultiesByUniversity($university_id): \Illuminate\Http\JsonResponse
    {
        // التحقق إذا الجامعة موجودة
        $university = University::with('faculties')->find($university_id);

        if (!$university) {
            return response()->json(['message' => 'University not found'], 404);
        }

        return response()->json([
            'university' => [
                'id' => $university->id,
                'name' => $university->name,
            ],
            'faculties' => $university->faculties->map(function ($faculty) {
                return [
                    'id' => $faculty->id,
                    'name' => $faculty->name,
                ];
            }),
        ]);
    }

}

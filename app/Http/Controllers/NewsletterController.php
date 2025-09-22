<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $newsletter = Newsletter::create([
                'email' => $validated['email'],
            ]);

            return response()->json([
                'message' => 'تم حفظ الإيميل بنجاح',
                'data' => $newsletter
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422); // هنا بنرجع 422 - unprocessable entity لو حصل خطأ فالـ validation
        }
    }
}

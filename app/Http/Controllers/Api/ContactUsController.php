<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends BaseController
{
    public function index()
    {
        $contact = ContactUs::first();

        if (!$contact) {
            return $this->singleItemResponse(null, "No contact data found.");
        }

        return $this->singleItemResponse($contact, "Contact info fetched successfully.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotline_number'   => 'nullable|string|max:20',
            'branch_number'    => 'nullable|string|max:20',
            'whatsapp_number'  => 'nullable|string|max:20',
        ]);

        // لو فيه بيانات قديمة امسحها (لو مسموح يكون سجل واحد بس)
        ContactUs::truncate();

        $contact = ContactUs::create([
            'hotline_number'   => $request->hotline_number,
            'branch_number'    => $request->branch_number,
            'whatsapp_number'  => $request->whatsapp_number,
        ]);

        return response()->json([
            'message' => 'تم إضافة بيانات الاتصال بنجاح',
            'data' => $contact
        ], 201);
    }

}

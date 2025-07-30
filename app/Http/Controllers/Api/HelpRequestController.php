<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Http\Request;

class HelpRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'mobile_number' => 'required|string',
            'type' => 'required|in:support,inquiry,lost_account,lost_card',
            'description' => 'required|string',
            'subject' => 'nullable|string',
            'sub_type' => 'nullable|string',
        ]);

        // فلترة إضافية حسب النوع
        if (in_array($request->type, ['support', 'inquiry'])) {
            if (!$request->subject) {
                return response()->json(['message' => 'Subject is required for support or inquiry.'], 422);
            }
            if (!$request->sub_type) {
                return response()->json(['message' => 'Type detail (support_type/inquiry_type) is required.'], 422);
            }
        }

        $help = HelpRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'type' => $request->type,
            'subject' => $request->subject,
            'sub_type' => $request->sub_type,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Your request has been submitted successfully.',
            'data' => $help
        ]);
    }

}

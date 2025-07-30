<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StartAdService;

class StartAdController extends Controller
{
    protected $service;

    public function __construct(StartAdService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $ad = $this->service->store($request);

        return response()->json([
            'message' => 'Start Ad created successfully',
            'data' => $ad
        ]);
    }

    public function show()
    {
        $ad = $this->service->get();

        return response()->json([
            'data' => $ad
        ]);
    }
}

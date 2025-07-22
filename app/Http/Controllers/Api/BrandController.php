<?php

namespace App\Http\Controllers\Api;
use App\Models\Brand;
use App\Services\BrandService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    protected $service;

    public function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $size = $request->input('size', 10);

        $brands = $this->service->allPaginated($size);

        return $this->successResponse($brands, "Brands fetched successfully.");
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'image' => '',
    ]);

    $brand = $this->service->create($data);

    return response()->json($brand, 201);
}


    public function show($id)
    {
        $brand = $this->service->find($id);
        return $this->singleItemResponse($brand, "Brand fetched successfully.");
    }


    public function update(Request $request, Brand $brand)
    {
        $data = $request->only(['name', 'image_path']);
        $brand = $this->service->update($brand, $data);

        return response()->json($brand);
    }

    public function destroy(Brand $brand)
    {
        $this->service->delete($brand);

        return response()->json(['message' => 'Deleted successfully.']);
    }
}

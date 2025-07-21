<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;

use App\Models\Brand;

class BrandRepository implements BrandRepositoryInterface
{
    public function all()
    {
        return Brand::all();
    }

    public function find($id)
    {
        return Brand::findOrFail($id);
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update(Brand $brand, array $data)
    {
        $brand->update($data);
        return $brand;
    }

    public function delete(Brand $brand)
    {
        return $brand->delete();
    }
}

<?php

namespace App\Services;

use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class BrandService
{
    protected $repository;

    public function __construct(BrandRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
    $brands = $this->repository->all();

    foreach ($brands as $brand) {
        if ($brand->image_path) {
            $brand->image_url = Storage::url($brand->image_path);
            unset($brand->image_path);
        } else {
            $brand->image_url = null;
        }
    }

    return $brands;    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
{
    if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
        $data['image_path'] = $data['image']->store('brand_images', 'public');
    } else {
        $data['image_path'] = null;
    }

    unset($data['image']);

    return $this->repository->create($data);
}

    public function update(Brand $brand, array $data)
    {
        return $this->repository->update($brand, $data);
    }

    public function delete(Brand $brand)
    {
        return $this->repository->delete($brand);
    }
}

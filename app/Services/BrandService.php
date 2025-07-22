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

    public function allPaginated($size)
    {
        $brands = $this->repository->allPaginated($size);

        foreach ($brands as $brand) {
            if ($brand->image_path) {
                $brand->image_url = Storage::url($brand->image_path);
                unset($brand->image_path);
            } else {
                $brand->image_url = null;
            }
        }

        return $brands;
    }

    public function find($id)
    {
        $brand = $this->repository->find($id);

        $brand->image_url = $brand->image_path
            ? Storage::url($brand->image_path)
            : null;

        unset($brand->image_path);

        return $brand;
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

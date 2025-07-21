<?php

namespace App\Repositories\Interfaces;

use App\Models\Brand;

interface BrandRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Brand $brand, array $data);
    public function delete(Brand $brand);
}

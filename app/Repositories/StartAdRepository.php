<?php

namespace App\Repositories;

use App\Models\StartAd;
use App\Repositories\Interfaces\StartAdRepositoryInterface;

class StartAdRepository implements StartAdRepositoryInterface
{
    public function create(array $data)
    {
        return StartAd::create($data);
    }

    public function getLatest()
    {
        return StartAd::latest()->first();
    }
}

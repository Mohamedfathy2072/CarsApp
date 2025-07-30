<?php

namespace App\Repositories\Interfaces;

interface StartAdRepositoryInterface
{
    public function create(array $data);
    public function getLatest();
}

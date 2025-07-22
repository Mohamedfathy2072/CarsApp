<?php

namespace App\Repositories\Interfaces;

interface SavedSearchInterface
{
public function store(array $data);
public function getByUser($userId, $limit);
public function delete($id);
}

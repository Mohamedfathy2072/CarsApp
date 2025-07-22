<?php
namespace App\Repositories;

use App\Models\SavedSearch;
use App\Repositories\Interfaces\SavedSearchInterface;

class SavedSearchRepository implements SavedSearchInterface
{
    public function store(array $data)
    {
        return SavedSearch::create($data);
    }

    public function getByUser($userId, $size)
    {
        return SavedSearch::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($size);
    }


    public function delete($id)
    {
        return SavedSearch::where('id', $id)->delete();
    }
}

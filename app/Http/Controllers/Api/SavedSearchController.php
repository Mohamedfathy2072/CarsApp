<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SavedSearchInterface;
use Illuminate\Http\Request;

class SavedSearchController extends BaseController
{
    protected $repository;

    public function __construct(SavedSearchInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'search_text' => 'required|string',
        ]);

        $savedSearch = $this->repository->store([
            'user_id' => auth('api')->id(),
            'search_text' => $request->search_text,
        ]);

        return response()->json($savedSearch);
    }

    public function index(Request $request)
    {
        $size = $request->input('size', 10);

        $searches = $this->repository->getByUser(auth('api')->id(), $size);

        return $this->successResponse($searches, "Saved searches fetched successfully.");
    }


    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}

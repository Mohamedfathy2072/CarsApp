<?php
namespace App\Services;

use App\Repositories\Interfaces\StartAdRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class StartAdService
{
    protected $repo;

    public function __construct(StartAdRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $path = $request->file('image')->store('start_ads', 'public');
        return $this->repo->create([
            'image_path' => $path
        ]);
    }

    public function get()
    {
        $ad = $this->repo->getLatest();

        if ($ad) {
            $ad->image_url = asset('storage/' . $ad->image_path);
            unset($ad->image_path);
        }

        return $ad;
    }

}

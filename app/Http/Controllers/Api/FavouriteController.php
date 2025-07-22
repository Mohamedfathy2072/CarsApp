<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavouriteController extends BaseController
{
    public function toggleFavourite($carId)
    {
        $user = auth('api')->user();
        $car = Car::findOrFail($carId);

        if ($user->favouriteCars()->where('car_id', $carId)->exists()) {
            // لو موجودة شيلها
            $user->favouriteCars()->detach($carId);

            return response()->json(['message' => 'Car removed to favourites']);
        } else {
            // لو مش موجودة ضيفها
            $user->favouriteCars()->attach($carId);

            return response()->json(['message' => 'Car added to favourites']);
        }
    }

    public function myFavourites(Request $request)
    {
        $user = auth('api')->user();

        // جلب قيم page و size من request
        $size = $request->query('size', 10);  // Default = 10 لو مش مبعوتة
        $page = $request->query('page', 1);   // Laravel بياخدها أوتوماتيك

        // تنفيذ Pagination بالـ size المبعت
        $favourites = $user->favouriteCars()
            ->with(['images', 'brand'])
            ->paginate($size);

        $favourites->getCollection()->transform(function ($car) {
            $car->images = $car->images->map(function ($image) {
                return [
                    'id'         => $image->id,
                    'car_id'     => $image->car_id,
                    'is_360'     => $image->is_360,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at,
                    'image_url'  => Storage::url($image->image_path),
                ];
            });

            $car->brand->image_url = $car->brand && $car->brand->image_path
                ? Storage::url($car->brand->image_path)
                : null;

            if (isset($car->brand->image_path)) {
                unset($car->brand->image_path);
            }

            return $car;
        });

        return $this->successResponse($favourites, 'Favourite cars fetched successfully.');
    }


}

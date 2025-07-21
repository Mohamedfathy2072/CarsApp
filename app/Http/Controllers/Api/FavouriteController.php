<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavouriteController extends Controller
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

    public function myFavourites()
    {
        $user = auth('api')->user();

        $favourites = $user->favouriteCars()->with(['images', 'brand'])->get();

        // تجهيز الصور واللينكات
        $favourites->transform(function ($car) {
            // تجهيز روابط صور السيارة
            $car->images->transform(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => Storage::url($image->image_path),
                ];
            });

            // تجهيز صورة البراند
            if ($car->brand && $car->brand->image_path) {
                $car->brand->image_url = Storage::url($car->brand->image_path);
                unset($car->brand->image_path);
               // dd( $car->brand->image_url);

            }

            return $car;
        });

        return response()->json($favourites);
    }
}

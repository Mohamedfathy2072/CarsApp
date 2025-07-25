<?php
namespace App\Services;

use App\Repositories\Interfaces\CarRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarService
{
    protected $repo;

    public function __construct(CarRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAllCars()
    {
        return $this->repo->all();
    }

    public function getCarById($id)
    {
        $car = $this->repo->find($id);
        return $this->formatCar($car);
    }


    public function createCar(Request $request)
    {
        $data = $request->only([
            'brand_id', 'model', 'year', 'color', 'transmission', 'engine_cc',
            'body_type', 'km_driven', 'price', 'down_payment', 'license_validity', 'location'
        ]);

        $car = $this->repo->create($data);

        // حفظ الصور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('car_images', 'public');

                $car->images()->create([
                    'image_path' => $path,
                    'is_360' => false
                ]);
            }
        }

         return $car->load(['images', 'brand']);
    }

    public function updateCar($id, Request $request)
    {
        $data = $request->only([
            'brand_id', 'model', 'year', 'color', 'transmission', 'engine_cc',
            'body_type', 'km_driven', 'price', 'down_payment', 'license_validity', 'location'
        ]);

        // لو فيه صور جديدة اترفعت في التحديث
        if ($request->hasFile('images')) {
            $car = $this->repo->find($id);

            // امسح الصور القديمة
            foreach ($car->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // ارفع الصور الجديدة
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('car_images', 'public');
                $car->images()->create([
                    'image_path' => $path,
                    'is_360' => false
                ]);
            }
        }

        $car = $this->repo->update($id, $data);

        return $car->load(['images', 'brand']);
    }


    public function deleteCar($id)
    {
        $car = $this->repo->find($id);

        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        return $this->repo->delete($id);
    }
    public function search($request)
    {
        return $this->repo->search($request);
    }

    public function formatCar($car)
    {
        foreach ($car->images as $image) {
            $image->image_url = Storage::url($image->image_path);
            unset($image->image_path);
        }

        if ($car->brand) {
            $car->brand->image_url = $car->brand->image_path
                ? Storage::url($car->brand->image_path)
                : null;
            unset($car->brand->image_path);
        }

        return $car;
    }

    public function formatCars($cars)
    {
        return $cars->getCollection()->transform(function ($car) {
            return $this->formatCar($car);
        });
    }


}

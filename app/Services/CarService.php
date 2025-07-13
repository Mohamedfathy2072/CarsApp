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
        return $this->repo->find($id);
    }

    public function createCar(Request $request)
    {
        $data = $request->only([
            'brand', 'model', 'year', 'color', 'transmission', 'engine_cc',
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

        return $car->load('images');
    }

    public function updateCar($id, Request $request)
    {
        $data = $request->only([
            'brand', 'model', 'year', 'color', 'transmission', 'engine_cc',
            'body_type', 'km_driven', 'price', 'down_payment', 'license_validity', 'location'
        ]);

        $car = $this->repo->update($id, $data);
        return $car->fresh('images');
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
}

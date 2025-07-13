<?php
namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Interfaces\CarRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class CarRepository implements CarRepositoryInterface
{
    public function all()
{
    $cars = Car::with('images')->get();

    foreach ($cars as $car) {
        foreach ($car->images as $image) {
            $image->image_url = Storage::url($image->image_path);
            unset($image->image_path); // ← ممكن تشيله لو مش عايزه يظهر
        }
    }

    return $cars;
}

    public function find($id)
    {
        return Car::with('images')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Car::create($data);
    }

    public function update($id, array $data)
    {
        $car = Car::findOrFail($id);
        $car->update($data);
        return $car;
    }

    public function delete($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return true;
    }

    //search

    public function search($request)
{
    $query = Car::query();

    if ($request->filled('brand')) {
        $query->where('brand', 'like', '%' . $request->brand . '%');
    }

    if ($request->filled('model')) {
        $query->where('model', 'like', '%' . $request->model . '%');
    }

    if ($request->filled('color')) {
        $query->where('color', 'like', '%' . $request->color . '%');
    }

    if ($request->filled('year')) {
        $query->where('year', $request->year);
    }

    if ($request->filled('price_from')) {
        $query->where('price', '>=', $request->price_from);
    }

    if ($request->filled('price_to')) {
        $query->where('price', '<=', $request->price_to);
    }

    if ($request->filled('location')) {
        $query->where('location', 'like', '%' . $request->location . '%');
    }

    $cars = $query->with('images')->get();

    foreach ($cars as $car) {
        foreach ($car->images as $image) {
            $image->image_url = Storage::url($image->image_path);
            unset($image->image_path);
        }
    }

    return $cars;
}


    //end search
}

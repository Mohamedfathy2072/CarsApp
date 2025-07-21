<?php
namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Interfaces\CarRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class CarRepository implements CarRepositoryInterface
{
    public function all()
    {
        $cars = Car::with(['images', 'brand'])->get();

        foreach ($cars as $car) {
            foreach ($car->images as $image) {
                $image->image_url = Storage::url($image->image_path);
                unset($image->image_path);
            }

            if ($car->brand) {
                $imagePath = $car->brand->image_path;

                $car->brand->image_url = $imagePath
                    ? Storage::url($imagePath)
                    : null;

                unset($car->brand->image_path);
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

        // فلترة حسب البراند
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->brand . '%');
            });
        }

        // فلترة حسب الموديل
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // فلترة حسب اللون
        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        // فلترة حسب السنة
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // فلترة حسب نوع الفتيس
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        // فلترة حسب السعر
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // فلترة حسب الكيلومترات
        if ($request->filled('km_from')) {
            $query->where('km_driven', '>=', $request->km_from);
        }

        if ($request->filled('km_to')) {
            $query->where('km_driven', '<=', $request->km_to);
        }

        // فلترة حسب المقدم
        if ($request->filled('down_payment_from')) {
            $query->where('down_payment', '>=', $request->down_payment_from);
        }

        if ($request->filled('down_payment_to')) {
            $query->where('down_payment', '<=', $request->down_payment_to);
        }
        // الأعمدة المسموح بترتيبها
        $allowedColumns = ['price', 'year', 'km_driven', 'model', 'created_at'];

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $column = $request->sort_by;
            $order = $request->sort_order;  // asc أو desc

            if (in_array($column, $allowedColumns)) {
                $query->orderBy($column, $order);
            }

        }
        // جلب البيانات مع الصور والبراند
        $cars = $query->with(['images', 'brand'])->get();

        // تجهيز الصور وروابطها
        foreach ($cars as $car) {
            foreach ($car->images as $image) {
                $image->image_url = Storage::url($image->image_path);
                unset($image->image_path);
            }

            if ($car->brand) {
                $car->brand->image_url = $car->brand->image_path ? Storage::url($car->brand->image_path) : null;
                unset($car->brand->image_path);
            }
        }


       //dd(in_array($column, $allowedColumns));
        return $cars;
    }


    //end search
}

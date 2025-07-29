<?php
namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Interfaces\CarRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class CarRepository implements CarRepositoryInterface
{
    public function all()
    {

        return Car::with(['images', 'brand']);

    }





    public function find($id)
    {
        return Car::with(['images', 'brand'])->findOrFail($id);
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

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%$search%")
                    ->orWhere('color', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%")
                    ->orWhere('year', 'like', "%$search%")
                    ->orWhereHas('brand', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        }

//dd($query);
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
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('condition')) {
            $conditions = is_array($request->condition) ? $request->condition : [$request->condition];
            $query->whereIn('condition', $conditions);
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



       //dd(in_array($column, $allowedColumns));
        return $query->with(['images', 'brand']);
    }


    //end search
}

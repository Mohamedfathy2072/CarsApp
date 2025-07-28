<?php

namespace App\Observers;

use App\Jobs\UpdateCarQuestionOptionsJob;
use App\Models\Car;
use App\Models\Quiz;

class CarObserver
{
    public function saved(Car $car): void
    {
        $attributesToCheck = [
            'body_type', 'condition', 'transmission', 'engine_cc', 'color',
            'location', 'year', 'license_validity','model'
        ];

        $data = [];

        foreach ($attributesToCheck as $attribute) {
            $data[$attribute] = $car->$attribute;
        }

        UpdateCarQuestionOptionsJob::dispatch($data);
    }
}

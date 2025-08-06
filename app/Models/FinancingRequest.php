<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancingRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'second_name',
        'email',
        'governorate_id',
        'area_id',
        'street',
        'building_number',
        'floor_number',
        'card_front',
        'card_back',
        'applicant_type',
        'university',
        'faculty',
        'company_name',
        'company_street',
        'company_building',
        'eco_position',
        'mid_salary',
        'car_type',
        'total_price',
        'down_payment',
        'deposit_percentage',
        'car_model',
        'manufacture_year',
        'car_brand',
        'brand_id',
        'status',
        'wallet',

        // المستندات الاختيارية
        'club_membership_card',
        'medical_insurance_card',
        'owned_car_license_front',
        'owned_car_license_back',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}

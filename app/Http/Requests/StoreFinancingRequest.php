<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'second_name' => 'required',
            'email' => 'required|email',
            'governorate_id' => 'required|exists:governorates,id',
            'area_id' => 'required|exists:areas,id',
            'street' => 'required',
            'building_number' => 'required',
            'floor_number' => 'nullable',
            'card_front' => 'required|image',
            'card_back' => 'required|image',
            'university_name' => 'required_if:applicant_type,student',
            'faculty_id' => 'required_if:applicant_type,student|exists:faculties,id',
            'applicant_type' => 'required|in:student,employee,unemployed',
            'brand_id' => 'required', 'exists:brands,id',
            'installment_plan' => 'required|in:12,36,60', // سنة أو 3 أو 5

            'company_name' => 'required_if:applicant_type,employee',
            'company_street' => 'required_if:applicant_type,employee',
            'company_building' => 'nullable',
            'eco_position' => 'nullable',
            'mid_salary' => 'nullable|numeric',
            'car_type' => 'required|in:new,used',
            'total_price' => 'required|numeric',
            'down_payment' => 'required|numeric',
            'deposit_percentage' => 'required|integer',
            'car_model' => 'required',
            'manufacture_year' => 'required|digits:4',
            'car_brand' => 'required',
            'club_membership_card' => 'nullable|image',
            'medical_insurance_card' => 'nullable|image',
            'owned_car_license_front' => 'nullable|image',
            'owned_car_license_back' => 'nullable|image',
            'reference_phone_number' => 'nullable',
            'person_relation' => 'nullable',


        ];
    }
}

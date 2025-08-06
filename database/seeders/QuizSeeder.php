<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = [
            [
                'question' => "What’s your occupation?",
                'attribute' => 'occupation',
                'options' => json_encode([
                    'Employee', 'Business owner', 'Freelancer', 'Housewife', 'Unemployed'
                ]),
            ],
            [
                'question' => "How much can you afford to pay upfront as a down payment?",
                'attribute' => 'down_payment_range',
                'options' => json_encode([
                    'Less than EGP 200,000',
                    'EGP 200,000 – 500,000',
                    'EGP 500,000 – 800,000',
                    'EGP 800,000 – 1,000,000',
                    'EGP 1,000,000 – 1,500,000',
                    'EGP 1,500,000 – 2,000,000',
                    'More than EGP 2,000,000',
                ]),
            ],
            [
                'question' => "How much can you afford monthly in installments?",
                'attribute' => 'monthly_installment_range',
                'options' => json_encode([
                    'Less than EGP 20,000',
                    'EGP 20,000 – 35,000',
                    'EGP 35,000 – 50,000',
                    'EGP 50,000 – 70,000',
                    'EGP 70,000 – 100,000',
                    'EGP 100,000 – 150,000',
                    'More than EGP 150,000',
                    'Not sure yet',
                ]),
            ],
            [
                'question' => "What’s your monthly income?",
                'attribute' => 'income',
                'options' => json_encode([
                    'Less than 25,000',
                    'EGP 25,000– 50,000',
                    'EGP 50,000– 80,000',
                    'EGP 80,000– 100,000',
                    'EGP 100,000- 150,000',
                    'EGP 150,000- 200,000',
                    'More than 200,000',
                ]),
            ],
            [
                'question' => "Are you currently paying any other installments?",
                'attribute' => 'has_other_installments',
                'options' => json_encode(['Yes', 'No']),
            ],
            [
                'question' => "If yes, how much do you pay monthly in other installments?",
                'attribute' => 'other_installments_value',
                'type' => 'text',
            ],
            [
                'question' => "What type of car are you looking for? (Choose up to 2)",
                'attribute' => 'car_types',
                'options' => json_encode([
                    'Sedan', 'SUV', 'Hatchback', 'Pickup', 'Coupe / Sport', "I'm not sure"
                ]),
            ],
            [
                'question' => "What’s your priority?",
                'attribute' => 'priority',
                'options' => json_encode([
                    'Low monthly installments',
                    'A practical car with minimal running expenses for daily use',
                    'Luxury feel',
                    'Resale value',
                    'Safety & family use'
                ]),
            ],
            [
                'question' => "How do you plan to use the car?",
                'attribute' => 'car_usage',
                'options' => json_encode([
                    'Daily commuting',
                    'Family car (school, groceries, etc.)',
                    'For work & business',
                    'Travel/long drives',
                    'Mix of everything',
                ]),
            ],
            [
                'question' => "Do you already have a specific car in mind?",
                'attribute' => 'specific_car',
                'type' => 'text',
            ],
            [
                'question' => "Will you trade-in your old car?",
                'attribute' => 'trade_in',
                'options' => json_encode(['Yes', 'No']),
            ],
            [
                'question' => "What’s your full name?",
                'attribute' => 'full_name',
                'type' => 'text',
            ],
            [
                'question' => "Mobile number",
                'attribute' => 'mobile',
                'type' => 'text',
            ],
            [
                'question' => "Preferred contact method",
                'attribute' => 'contact_method',
                'options' => json_encode(['WhatsApp', 'Phone call', 'Doesn’t matter']),
            ],
        ];

        foreach ($quizzes as $quiz) {
            DB::table('quizzes')->updateOrInsert(
                ['attribute' => $quiz['attribute']],
                array_merge([
                    'question' => $quiz['question'],
                    'type' => $quiz['type'] ?? 'select',
                    'is_required' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], isset($quiz['options']) ? ['options' => $quiz['options']] : [])
            );
        }
    }
}

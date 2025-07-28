<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'attribute' => 'body_type',
                'question' => 'ما نوع السيارة التي تفضلها؟',
                'options' => ['سيدان', 'هاتشباك', 'SUV', 'بيك أب', 'كوبيه', 'فان'],
            ],
            [
                'attribute' => 'condition',
                'question' => 'ما الحالة التي تفضلها؟',
                'options' => ['جديدة', 'مستعملة'],
            ],
            [
                'attribute' => 'price',
                'question' => 'ما هو نطاق السعر المناسب لك؟',
                'options' => ['أقل من 200,000', 'من 200,000 إلى 400,000', 'أكثر من 400,000'],
            ],
            [
                'attribute' => 'km_driven',
                'question' => 'ما هي المسافة القصوى التي قطعتها السيارة (كم)؟',
                'options' => ['أقل من 50,000', 'من 50,000 إلى 100,000', 'أكثر من 100,000'],
            ],
            [
                'attribute' => 'transmission',
                'question' => 'هل تفضل ناقل حركة أوتوماتيك أم مانيوال؟',
                'options' => ['أوتوماتيك', 'مانيوال'],
            ],
            [
                'attribute' => 'engine_cc',
                'question' => 'ما نوع المحرك الذي يناسبك؟',
                'options' => ['أقل من 1200cc', 'من 1200 إلى 1600cc', 'أكثر من 1600cc'],
            ],
            [
                'attribute' => 'year',
                'question' => 'ما هو الموديل الأدنى الذي تبحث عنه؟',
                'options' => ['2015', '2018', '2020', '2023'],
            ],
            [
                'attribute' => 'color',
                'question' => 'ما اللون المفضل للسيارة؟',
                'options' => ['أسود', 'أبيض', 'فضي', 'أحمر', 'أزرق', 'رمادي'],
            ],
            [
                'attribute' => 'license_validity',
                'question' => 'هل السيارة يجب أن تكون مرخصة؟',
                'options' => ['نعم', 'لا يهم'],
            ],
            [
                'attribute' => 'location',
                'question' => 'في أي محافظة تبحث عن السيارة؟',
                'options' => ['القاهرة', 'الجيزة', 'الإسكندرية', 'المنصورة', 'طنطا', 'أسيوط'],
            ],
            [
                'attribute' => 'brand_id',
                'question' => 'ما هي ماركة السيارة المفضلة لديك؟',
                'options' => []
            ],
            [
                'attribute' => 'model',
                'question' => 'ما هو موديل السيارة المفضل لديك؟',
                'options' => [] // هيتعبى بالـ observer زي ما عملنا
            ]
        ];

        foreach ($questions as $q) {
            Quiz::create([
                'attribute' => $q['attribute'],
                'question' => $q['question'],
                'options' => $q['options'],
            ]);
        }
    }
}

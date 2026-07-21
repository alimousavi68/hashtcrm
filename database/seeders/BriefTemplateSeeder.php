<?php

namespace Database\Seeders;

use App\Models\BriefTemplate;
use Illuminate\Database\Seeder;

class BriefTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BriefTemplate::truncate();

        BriefTemplate::create([
            'name' => 'پرسشنامه طراحی سایت',
            'is_active' => true,
            'wizard_mode' => true,
            'views_count' => 268,
            'responses_count' => 18,
            'schema' => [
                [
                    'type' => 'text_input',
                    'data' => [
                        'name' => 'business_name',
                        'label' => 'نام برند / کسب‌وکار',
                        'placeholder' => 'مثال: فروشگاه آنلاین هشت',
                        'required' => true,
                    ]
                ],
                [
                    'type' => 'textarea',
                    'data' => [
                        'name' => 'business_description',
                        'label' => 'توصیف خدمات و فعالیت کسب‌وکار',
                        'placeholder' => 'توضیح دهید کسب‌وکار شما چه کار می‌کند...',
                        'required' => true,
                    ]
                ],
                [
                    'type' => 'textarea',
                    'data' => [
                        'name' => 'target_audience',
                        'label' => 'مخاطبان هدف',
                        'placeholder' => 'مشتریان شما چه کسانی هستند؟',
                        'required' => false,
                    ]
                ],
                [
                    'type' => 'textarea',
                    'data' => [
                        'name' => 'competitors',
                        'label' => 'رقبای اصلی شما',
                        'placeholder' => 'نام یا آدرس وب‌سایت رقبای خود را بنویسید...',
                        'required' => false,
                    ]
                ],
                [
                    'type' => 'select',
                    'data' => [
                        'name' => 'design_style',
                        'label' => 'سبک طراحی مورد پسند',
                        'options' => 'مینیمال و ساده,شرکتی و رسمی,مدرن و تیره,خلاقانه و پویا',
                        'required' => true,
                    ]
                ],
            ]
        ]);

        BriefTemplate::create([
            'name' => 'پرسشنامه طراحی سایت - ۲',
            'is_active' => true,
            'wizard_mode' => true,
            'views_count' => 17,
            'responses_count' => 4,
            'schema' => [
                [
                    'type' => 'text_input',
                    'data' => [
                        'name' => 'brand_identity',
                        'label' => 'هویت بصری و پالت رنگی',
                        'placeholder' => 'رنگ‌های سازمانی مورد نظر شما',
                        'required' => true,
                    ]
                ],
                [
                    'type' => 'textarea',
                    'data' => [
                        'name' => 'feature_requirements',
                        'label' => 'امکانات و نیازمندی‌های کلیدی',
                        'placeholder' => 'امکانات مورد نیاز مانند درگاه، زبان دوم و...',
                        'required' => true,
                    ]
                ],
            ]
        ]);

        BriefTemplate::create([
            'name' => 'پرسشنامه طراحی سایت - خالی',
            'is_active' => false,
            'wizard_mode' => false,
            'views_count' => 0,
            'responses_count' => 0,
            'schema' => []
        ]);
    }
}

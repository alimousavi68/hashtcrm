<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BriefTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BriefTemplate::create([
            'name' => 'قالب پیش‌فرض بریف پروژه',
            'is_active' => true,
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
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'ادمین سیستم',
            'email' => 'admin@test.com',
            'phone' => '09120000000',
            'role' => 'admin',
        ]);

        $client = User::factory()->create([
            'name' => 'مشتری تست',
            'email' => 'client@test.com',
            'phone' => '09121111111',
            'role' => 'client',
        ]);

        $project = \App\Models\Project::create([
            'client_id' => $client->id,
            'title' => 'پلتفرم تجارت الکترونیک هشت',
            'status' => 'contract',
            'feedback_deadline' => now()->addDays(7),
        ]);

        \App\Models\Contract::create([
            'project_id' => $project->id,
            'title' => 'قرارداد همکاری طراحی و توسعه وب‌سایت هشت',
            'content' => '<p>این قرارداد بین شرکت هشت و جناب آقای/سرکار خانم <strong>:client_name</strong> جهت اجرای پروژه <strong>:project_title</strong> در تاریخ <strong>:date</strong> منعقد می‌گردد.</p><p>مبلغ پیش‌پرداخت معادل ۵۰٪ کل مبلغ توافق شده می‌باشد.</p>',
        ]);
    }
}

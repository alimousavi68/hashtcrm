<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\BriefAnswer;
use App\Models\ProjectCredential;
use App\Models\Feedback;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Handover;
use App\Models\BriefTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Core Users (Admin and Clients)
        $admin = User::updateOrCreate(
            ['phone' => '09120000000'],
            [
                'name' => 'ادمین سیستم',
                'email' => 'admin@test.com',
                'role' => 'admin',
            ]
        );

        $client1 = User::updateOrCreate(
            ['phone' => '09121111111'],
            [
                'name' => 'احمد حسینی',
                'email' => 'ahmad@test.com',
                'role' => 'client',
            ]
        );

        $client2 = User::updateOrCreate(
            ['phone' => '09122222222'],
            [
                'name' => 'مریم رضایی',
                'email' => 'maryam@test.com',
                'role' => 'client',
            ]
        );

        $client3 = User::updateOrCreate(
            ['phone' => '09123333333'],
            [
                'name' => 'سعید عباسی',
                'email' => 'saeed@test.com',
                'role' => 'client',
            ]
        );

        // Disable foreign keys to safely truncate tables for seeding
        Schema::disableForeignKeyConstraints();
        TicketMessage::truncate();
        Ticket::truncate();
        Feedback::truncate();
        Handover::truncate();
        ProjectCredential::truncate();
        BriefAnswer::truncate();
        BriefTemplate::truncate();
        Payment::truncate();
        Contract::truncate();
        Project::truncate();
        DB::table('notifications')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(BriefTemplateSeeder::class);

        // --- Scenario 1: Completed & Settled Project (Client 1) ---
        $projectCompleted = Project::create([
            'client_id' => $client1->id,
            'title' => 'پلتفرم تجارت الکترونیک کتاب کده',
            'status' => 'completed',
            'is_settled' => true,
            'demo_url' => 'https://bookkade-demo.hasht.dev',
            'feedback_deadline' => Carbon::now()->subDays(5),
        ]);

        Contract::create([
            'project_id' => $projectCompleted->id,
            'title' => 'قرارداد همکاری طراحی و توسعه وب‌سایت کتاب‌کده',
            'content' => '<p>این قرارداد جهت اجرای پروژه <strong>:project_title</strong> بین طرفین امضا می‌گردد.</p>',
            'signed_at' => Carbon::now()->subDays(30),
            'signature_name' => $client1->name,
            'signature_national_code' => '0012345678',
        ]);

        Payment::create([
            'project_id' => $projectCompleted->id,
            'amount' => 15000000,
            'status' => 'approved',
            'bank_slip_path' => 'bank-slips/seed-completed-slip.png',
        ]);

        ProjectCredential::create([
            'project_id' => $projectCompleted->id,
            'host_provider' => 'IranServer',
            'host_username' => 'bookkade_user',
            'host_password' => 'SecurePass123!',
            'host_panel_url' => 'https://iranserver.com:2083',
            'domain_provider' => 'Nic.ir',
            'domain_username' => 'ahmad-nic',
            'domain_password' => 'NicPass987',
        ]);

        Handover::create([
            'project_id' => $projectCompleted->id,
            'congratulations_message' => '<h3>پروژه شما با موفقیت تحویل شد!</h3><p>تمام مراحل توسعه با موفقیت پایان یافته است و وب‌سایت شما هم‌اکنون در دسترس عموم قرار دارد.</p>',
            'training_videos' => [
                ['title' => 'آموزش کار با پنل مدیریت وردپرس', 'url' => 'https://aparat.com/v/example1'],
                ['title' => 'نحوه افزودن محصول جدید', 'url' => 'https://aparat.com/v/example2'],
            ],
            'final_credentials' => "آدرس مدیریت: https://bookkade.ir/wp-admin\nنام کاربری: admin_bookkade\nکلمه عبور: Bookkade@2026",
        ]);

        // --- Scenario 2: Project in Review Phase (Client 2) ---
        $projectReview = Project::create([
            'client_id' => $client2->id,
            'title' => 'پورتال رسمی بیمه سینا',
            'status' => 'review',
            'is_settled' => false,
            'demo_url' => 'https://sina-insurance.hasht.dev',
            'feedback_deadline' => Carbon::now()->addDays(3),
        ]);

        Contract::create([
            'project_id' => $projectReview->id,
            'title' => 'قرارداد طراحی پرتال بیمه سینا',
            'content' => '<p>قرارداد طراحی پرتال بیمه سینا برای پروژه <strong>:project_title</strong> در تاریخ <strong>:date</strong> منعقد شد.</p>',
            'signed_at' => Carbon::now()->subDays(15),
            'signature_name' => $client2->name,
            'signature_national_code' => '0087654321',
        ]);

        Payment::create([
            'project_id' => $projectReview->id,
            'amount' => 20000000,
            'status' => 'approved',
            'bank_slip_path' => 'bank-slips/seed-review-slip.png',
        ]);

        BriefAnswer::create([
            'project_id' => $projectReview->id,
            'business_name' => 'بیمه نمایندگی سینا',
            'business_description' => 'ارائه دهنده کلیه خدمات بیمه‌ای آنلاین در سراسر کشور',
            'design_style' => 'corporate',
            'color_preferences' => 'سرمه‌ای و نقره‌ای',
            'features_required' => ['blog', 'user_panel'],
        ]);

        // Feedback for project review
        Feedback::create([
            'project_id' => $projectReview->id,
            'notes' => 'لطفاً رنگ دکمه‌های صفحه اصلی به سرمه‌ای پررنگ‌تر تغییر کند.',
            'status' => 'needs_changes',
        ]);

        // Tickets for project review
        $ticket1 = Ticket::create([
            'project_id' => $projectReview->id,
            'client_id' => $client2->id,
            'subject' => 'درخواست افزودن درگاه زرین‌پال به پرتال',
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket1->id,
            'sender_id' => $client2->id,
            'message' => 'سلام، درگاه زرین‌پال فعال شده است ولی نیاز داریم آن را به فرم‌های ثبت‌نام بیمه متصل کنید.',
        ]);

        $ticket2 = Ticket::create([
            'project_id' => $projectReview->id,
            'client_id' => $client2->id,
            'subject' => 'سوال درباره بارگذاری مدارک پزشکان',
            'status' => 'replied',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket2->id,
            'sender_id' => $client2->id,
            'message' => 'آیا امکان آپلود فایل PDF برای مدارک پزشکان وجود دارد؟',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket2->id,
            'sender_id' => $admin->id,
            'message' => 'بله مریم خانم، فرمت‌های مجاز برای آپلود PDF, JPG, PNG با حداکثر حجم ۵ مگابایت تنظیم شده است.',
        ]);

        // --- Scenario 3: Project in Brief Phase (Client 3) ---
        $projectBrief = Project::create([
            'client_id' => $client3->id,
            'title' => 'اپلیکیشن موبایل کلینیک زیبایی آریا',
            'status' => 'brief',
            'is_settled' => false,
            'feedback_deadline' => null,
        ]);

        // Ticket Closed for Client 3
        $ticket3 = Ticket::create([
            'project_id' => $projectBrief->id,
            'client_id' => $client3->id,
            'subject' => 'راهنمایی در مورد تکمیل پرسشنامه بریف',
            'status' => 'closed',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket3->id,
            'sender_id' => $client3->id,
            'message' => 'من بخش سبک طراحی را متوجه نمی‌شوم، آیا نمونه کار دارید ارسال کنید؟',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket3->id,
            'sender_id' => $admin->id,
            'message' => 'سلام جناب عباسی، در همان صفحه توضیحات کوتاهی داده شده است. همچنین می‌توانید با شماره پشتیبانی تماس بگیرید.',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket3->id,
            'sender_id' => $client3->id,
            'message' => 'ممنون، تماس گرفتم و مشکلم برطرف شد. تیکت را ببندید.',
        ]);

        // --- Notifications Seeding ---
        // 1. Notification for Admin about a new brief answer submission
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\ProjectNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $admin->id,
            'data' => json_encode([
                'title' => 'ثبت بریف جدید',
                'body' => "مشتری برای پروژه «{$projectReview->title}» پرسشنامه بریف و دسترسی‌ها را تکمیل نمود.",
                'category' => 'projects',
                'viewData' => ['category' => 'projects'],
            ]),
            'read_at' => null,
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        // 2. Notification for Admin about a payment bank slip
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\ProjectNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $admin->id,
            'data' => json_encode([
                'title' => 'ثبت فیش واریز جدید',
                'body' => "مشتری برای پروژه «{$projectReview->title}» فیش واریزی جدیدی به مبلغ ۲۰,۰۰۰,۰۰۰ تومان بارگذاری کرد.",
                'category' => 'financial',
                'viewData' => ['category' => 'financial'],
            ]),
            'read_at' => Carbon::now()->subHour(),
            'created_at' => Carbon::now()->subHours(4),
            'updated_at' => Carbon::now()->subHours(4),
        ]);

        // 3. Notification for Client 2 about Demo URL submission
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\ProjectNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $client2->id,
            'data' => json_encode([
                'title' => 'آماده‌سازی نسخه دمو',
                'body' => "دموی اولیه پروژه «{$projectReview->title}» آماده بازنگری و ثبت فیدبک شماست.",
                'category' => 'projects',
                'viewData' => ['category' => 'projects'],
            ]),
            'read_at' => null,
            'created_at' => Carbon::now()->subMinutes(30),
            'updated_at' => Carbon::now()->subMinutes(30),
        ]);
    }
}

<x-filament-panels::page>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        .vazir-font, .fi-body, .fi-page, .fi-header, .fi-ta, .fi-fo {
            font-family: 'Vazirmatn', sans-serif !important;
        }
    </style>

    <div class="space-y-6 vazir-font">
        <!-- Dashboard Overview List -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm space-y-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">خوش آمدید، {{ Auth::user()->name }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">به پرتال مشتریان شرکت هشت خوش آمدید. از منوی سایدبار می‌توانید پروژه‌ها و پشتیبانی خود را مدیریت و پیگیری نمایید.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                    <span class="text-[11px] text-gray-500 dark:text-gray-440 block">کل پروژه‌ها</span>
                    <span class="text-2xl font-black mt-2 block text-gray-950 dark:text-white">{{ $totalProjects }}</span>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                    <span class="text-[11px] text-gray-500 dark:text-gray-440 block">پروژه‌های فعال</span>
                    <span class="text-2xl font-black mt-2 block text-gray-950 dark:text-white">{{ $activeProjects }}</span>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                    <span class="text-[11px] text-gray-500 dark:text-gray-440 block">پروژه‌های پایان‌یافته</span>
                    <span class="text-2xl font-black mt-2 block text-gray-950 dark:text-white">{{ $completedProjects }}</span>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                    <span class="text-[11px] text-gray-500 dark:text-gray-440 block">تیکت‌های باز پشتیبانی</span>
                    <span class="text-2xl font-black mt-2 block text-gray-950 dark:text-white">{{ $openTickets }}</span>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm flex flex-col justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">مشاهده و پیگیری پروژه‌ها</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">مشاهده فازهای پیشرفت، تکمیل بریف، امضای قرارداد و بررسی دموهای طراحی شده.</p>
                </div>
                <a href="{{ route('filament.client.pages.projects') }}" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors text-center block">
                    ورود به بخش پروژه‌ها
                </a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm flex flex-col justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">پشتیبانی و تیکت‌ها</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ارتباط مستقیم با کارشناسان فنی، ارسال پاسخ به تیکت‌ها و طرح سوالات مربوط به پروژه.</p>
                </div>
                <a href="{{ route('filament.client.pages.tickets') }}" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors text-center block">
                    ورود به پشتیبانی
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>

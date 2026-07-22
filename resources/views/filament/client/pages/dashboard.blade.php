<x-filament-panels::page>
    <style>
        .hasht-client-container { display: flex; flex-direction: column; gap: 20px; font-family: 'PeydaWebVF', sans-serif !important; direction: rtl; }
        .hasht-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        .hasht-sec-title { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; }

        .hasht-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }
        .hasht-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .hasht-banner { position: relative; overflow: hidden; border-radius: 12px; padding: 18px 22px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .hasht-banner-amber { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .hasht-banner-indigo { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
        .hasht-banner-blue { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
        .hasht-banner-purple { background: #faf5ff; border-color: #e9d5ff; color: #6b21a8; }
        .hasht-banner-emerald { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .hasht-banner-sky { background: #f0f9ff; border-color: #bae6fd; color: #075985; }
        .hasht-banner-gray { background: #f8fafc; border-color: #e2e8f0; color: #334155; }

        .hasht-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        .hasht-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
        @media (max-width: 768px) {
            .hasht-grid-4 { grid-template-columns: repeat(2, 1fr); }
            .hasht-grid-2 { grid-template-columns: 1fr; }
        }

        .hasht-stat-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; min-height: 95px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .hasht-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #f1f5f9; color: #334155; white-space: nowrap; }

        .hasht-manage-btn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s; font-size: 13px; cursor: pointer; }
        .hasht-manage-btn:hover { background: #e0e7ff; border-color: #a5b4fc; box-shadow: 0 2px 6px rgba(67,56,202,0.12); }

        .hasht-manage-btn-green { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #15803d; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; transition: all 0.2s; font-size: 13px; cursor: pointer; }
        .hasht-manage-btn-green:hover { background: #dcfce7; border-color: #86efac; }

        svg { flex-shrink: 0; }
    </style>

    <div class="hasht-client-container">
        <!-- HERO NEXT ACTION BANNER -->
        @if($nextAction)
            @php
                $bannerClass = 'hasht-banner-' . ($nextAction['color'] ?? 'indigo');
            @endphp
            <div class="hasht-banner {{ $bannerClass }}">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="padding: 10px; background: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <svg style="width: 22px; height: 22px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="hasht-badge" style="background: #ffffff; border: 1px solid #cbd5e1;">{{ $nextAction['badge'] }}</span>
                            <h3 style="font-size: 15px; font-weight: 800; margin: 0;">{{ $nextAction['title'] }}</h3>
                        </div>
                        <p style="font-size: 13px; margin-top: 4px; opacity: 0.9; line-height: 1.6;">
                            {{ $nextAction['description'] }}
                        </p>
                    </div>
                </div>

                @if($nextAction['buttonText'])
                    <div>
                        <a href="{{ $nextAction['url'] }}" class="hasht-manage-btn">
                            <span>{{ $nextAction['buttonText'] }}</span>
                            <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Stats Overview Row -->
        <div class="hasht-card">
            <div class="hasht-sec-heading">
                <div class="hasht-sec-title">
                    <svg style="width: 20px; height: 20px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>خلاصه وضعیت حساب کاربری شما</span>
                </div>
                @if($latestProject)
                    <a href="{{ route('filament.client.pages.projects') }}" class="hasht-manage-btn">
                        <span>ورود به میزکار پروژه جاری</span>
                        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>
            
            <div class="hasht-grid-4">
                <div class="hasht-stat-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 13px; font-weight: 700; color: #475569;">کل پروژه‌ها</span>
                        <div style="padding: 6px; background: #eef2ff; border-radius: 8px; color: #4338ca;">
                            <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    </div>
                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; margin-top: 10px;">{{ \App\Helpers\JalaliHelper::toPersianDigits($totalProjects) }}</span>
                </div>

                <div class="hasht-stat-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 13px; font-weight: 700; color: #475569;">پروژه‌های فعال</span>
                        <div style="padding: 6px; background: #fffbeb; border-radius: 8px; color: #d97706;">
                            <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                    </div>
                    <span style="font-size: 26px; font-weight: 900; color: #4f46e5; margin-top: 10px;">{{ \App\Helpers\JalaliHelper::toPersianDigits($activeProjects) }}</span>
                </div>

                <div class="hasht-stat-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 13px; font-weight: 700; color: #475569;">پروژه‌های پایان‌یافته</span>
                        <div style="padding: 6px; background: #f0fdf4; border-radius: 8px; color: #16a34a;">
                            <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <span style="font-size: 26px; font-weight: 900; color: #16a34a; margin-top: 10px;">{{ \App\Helpers\JalaliHelper::toPersianDigits($completedProjects) }}</span>
                </div>

                <div class="hasht-stat-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 13px; font-weight: 700; color: #475569;">تیکت‌های باز</span>
                        <div style="padding: 6px; background: #faf5ff; border-radius: 8px; color: #9333ea;">
                            <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                    </div>
                    <span style="font-size: 26px; font-weight: 900; color: #9333ea; margin-top: 10px;">{{ \App\Helpers\JalaliHelper::toPersianDigits($openTickets) }}</span>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="hasht-grid-2">
            <div class="hasht-card" style="display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                <div style="display: flex; align-items: flex-start; gap: 14px;">
                    <div style="padding: 12px; background: #eef2ff; border-radius: 10px; color: #4f46e5;">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 012.447 15.5V5.5a2 2 0 011.106-1.789L9 1m0 19v-9m0 9l5.447-2.724A2 2 0 0019.553 15.5V5.5a2 2 0 00-1.106-1.789L15 1m-6 9V1m0 9l6-3.333"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0;">میزکار و پیگیری پروژه‌ها</h3>
                        <p style="font-size: 13px; color: #475569; margin-top: 6px; line-height: 1.7;">مشاهده فازهای پیشرفت، تکمیل بریف نیازمندی‌ها، امضای دیجیتال قرارداد و بررسی دموی طراحی‌شده.</p>
                    </div>
                </div>
                <a href="{{ route('filament.client.pages.projects') }}" class="hasht-manage-btn" style="width: fit-content;">
                    <span>ورود به بخش پروژه‌ها</span>
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
            </div>

            <div class="hasht-card" style="display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                <div style="display: flex; align-items: flex-start; gap: 14px;">
                    <div style="padding: 12px; background: #f0fdf4; border-radius: 10px; color: #16a34a;">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0;">پشتیبانی و چت آنلاین تیکت‌ها</h3>
                        <p style="font-size: 13px; color: #475569; margin-top: 6px; line-height: 1.7;">ارتباط مستقیم با کارشناسان فنی پروژه، پاسخگویی به تیکت‌ها و دریافت راهنمایی‌های فنی.</p>
                    </div>
                </div>
                <a href="{{ route('filament.client.pages.tickets') }}" class="hasht-manage-btn-green" style="width: fit-content;">
                    <span>ورود به مرکز پشتیبانی</span>
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>

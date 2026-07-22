<x-filament-widgets::widget>
    @php $stats = $this->getProjectsStats(); @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 16px;">

        <!-- کارت ۱: وضعیت کلی پروژه‌ها -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">پروژه‌های فعال سیستم</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #eef2ff; color: #4338ca; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932h14.388a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #0f172a;">
                    {{ $stats['active_count'] }} <span style="font-size: 12px; font-weight: 700; color: #475569;">پروژه فعال</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    از مجموع <strong style="color: #0f172a;">{{ $stats['total_projects'] }}</strong> پروژه | <span style="color: #166534; font-weight: 700;">{{ $stats['completed_count'] }} تکمیلی</span>
                </div>
            </div>
        </div>

        <!-- کارت ۲: گلوگاه نیازمند اقدام -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">اقدامات معطل ادمین</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #fffbeb; color: #b45309; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #b45309;">
                    {{ $stats['action_required_count'] }} <span style="font-size: 12px; font-weight: 700; color: #b45309;">مورد اقدام</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span>بریف: <strong>{{ $stats['pending_briefs'] }}</strong></span>
                    <span>فیش: <strong>{{ $stats['pending_payments'] }}</strong></span>
                    <span>تیکت: <strong>{{ $stats['open_tickets'] }}</strong></span>
                </div>
            </div>
        </div>

        <!-- کارت ۳: وضعیت ددلاین‌ها -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">پروژه‌های دارای تاخیر</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #ffe4e6; color: #be123c; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: {{ $stats['raw_overdue'] > 0 ? '#be123c' : '#166534' }};">
                    {{ $stats['overdue_count'] }} <span style="font-size: 12px; font-weight: 700;">پروژه تاخیردار</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    @if($stats['raw_overdue'] > 0)
                        نیازمند تسریع در پیگیری و هماهنگی با کارفرما
                    @else
                        ✅ تمامی ددلاین‌های بازنگری منظم است
                    @endif
                </div>
            </div>
        </div>

        <!-- کارت ۴: میانگین پیشرفت کل -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">میانگین پیشرفت پروژه‌ها</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #dcfce7; color: #166534; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-1-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.25 2.25L15 7.5" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #166534;">
                    ٪{{ $stats['avg_progress'] }} <span style="font-size: 12px; font-weight: 700; color: #475569;">پیشرفت میانگین</span>
                </div>
                <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; margin-top: 6px;">
                    <div style="width: {{ $stats['raw_avg_progress'] }}%; height: 100%; background: #10b981; border-radius: 9999px;"></div>
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>

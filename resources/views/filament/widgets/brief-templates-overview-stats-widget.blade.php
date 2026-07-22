<x-filament-widgets::widget>
    @php $stats = $this->getBriefStats(); @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 16px;">

        <!-- کارت ۱: الگوهای پرسشنامه -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">کل الگوهای پرسشنامه</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #eef2ff; color: #4338ca; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #0f172a;">
                    {{ $stats['total_count'] }} <span style="font-size: 12px; font-weight: 700; color: #475569;">الگوی بریف</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    <span style="color: #166534; font-weight: 700;">{{ $stats['active_count'] }} فعال</span> | <span style="color: #991b1b; font-weight: 600;">{{ $stats['inactive_count'] }} غیرفعال</span>
                </div>
            </div>
        </div>

        <!-- کارت ۲: پاسخ‌های دریافتی -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">پاسخ‌های ثبت‌شده کارفرمایان</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #15803d;">
                    {{ $stats['total_responses'] }} <span style="font-size: 12px; font-weight: 700; color: #15803d;">پاسخ دریافتی</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    ثبت‌شده روی پروژه‌های فعال CRM
                </div>
            </div>
        </div>

        <!-- کارت ۳: پرسشنامه‌های ویزاردی -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">پرسشنامه‌های ویزاردی</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #b45309;">
                    {{ $stats['wizard_count'] }} <span style="font-size: 12px; font-weight: 700; color: #b45309;">الگوی گام‌به‌گام</span>
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    دارای تفکیک گروه‌بندی مراحل برای کارفرما
                </div>
            </div>
        </div>

        <!-- کارت ۴: پرکاربردترین الگو -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #334155;">جامع‌ترین الگوی بریف</span>
                <div style="width: 30px; height: 30px; border-radius: 8px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 17px; height: 17px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 14px; font-weight: 800; color: #0369a1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $stats['top_template_name'] }}
                </div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    شامل <strong style="color: #0f172a;">{{ $stats['top_questions_count'] }}</strong> فیلد و نیازمندی تخصصی
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>

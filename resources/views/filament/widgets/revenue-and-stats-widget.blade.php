<x-filament-widgets::widget>
    @php $data = $this->getWidgetData(); @endphp

    <div x-data="{
        showAmounts: localStorage.getItem('hasht_show_revenue') === 'true',
        toggleShow() {
            this.showAmounts = !this.showAmounts;
            localStorage.setItem('hasht_show_revenue', this.showAmounts);
        }
    }" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">

        <!-- کارت ۱: پروژه‌های فعال و ثبت‌شده -->
        <div style="background: var(--card-bg, #ffffff); border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b;">پروژه‌های فعال</span>
                <div style="width: 28px; height: 28px; border-radius: 8px; background: #eef2ff; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                    <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12.75M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #0f172a;">
                    {{ $data['active_projects'] }} <span style="font-size: 12px; font-weight: 600; color: #64748b;">پروژه فعال</span>
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    از مجموع <strong style="color: #334155;">{{ $data['total_projects'] }}</strong> پروژه ثبت‌شده
                </div>
            </div>
        </div>

        <!-- کارت ۲: فیش‌های در انتظار بررسی -->
        <div style="background: var(--card-bg, #ffffff); border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b;">فیش‌های در انتظار تایید</span>
                <div style="width: 28px; height: 28px; border-radius: 8px; background: #fffbeb; display: flex; align-items: center; justify-content: center; color: #d97706;">
                    <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #d97706;">
                    {{ $data['pending_payments_count'] }} <span style="font-size: 12px; font-weight: 600; color: #d97706;">فیش</span>
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    مجموع: <span x-show="showAmounts" style="font-weight: 700;">{{ $data['pending_payments_sum'] }} تومان</span><span x-show="!showAmounts">••••••••</span>
                </div>
            </div>
        </div>

        <!-- کارت ۳: تیکت‌های پشتیبانی -->
        <div style="background: var(--card-bg, #ffffff); border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b;">تیکت‌های در انتظار پاسخ</span>
                <div style="width: 28px; height: 28px; border-radius: 8px; background: #ffe4e6; display: flex; align-items: center; justify-content: center; color: #e11d48;">
                    <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 5.97 5.97 0 0 1 1.057-2.038A8.25 8.25 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </div>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 900; color: #be123c;">
                    {{ $data['open_tickets'] }} <span style="font-size: 12px; font-weight: 600; color: #be123c;">تیکت باز</span>
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    نیازمند پاسخگویی ادمین
                </div>
            </div>
        </div>

        <!-- کارت ۴: درآمدهای مالی با قابلیت ماسک / نمایش حریم خصوصی -->
        <div style="background: var(--card-bg, #ffffff); border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b;">درآمد تاییدشده</span>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <!-- دکمه مخفی/نمایش مبالغ -->
                    <button @click="toggleShow()" type="button" title="نمایش/مخفی کردن مبالغ" 
                            style="background: #f1f5f9; border: none; padding: 4px 6px; border-radius: 6px; cursor: pointer; color: #475569; display: flex; align-items: center; justify-content: center;">
                        <svg x-show="!showAmounts" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg x-show="showAmounts" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                    <div style="width: 28px; height: 28px; border-radius: 8px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #10b981;">
                        <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <div style="font-size: 18px; font-weight: 800; color: #047857;">
                    <span x-show="showAmounts">{{ $data['monthly_revenue'] }} تومان</span>
                    <span x-show="!showAmounts">••••••••</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 4px; margin-top: 4px; font-size: 10px; color: #64748b;">
                    <span>سال: <strong x-show="showAmounts" style="color: #334155;">{{ $data['yearly_revenue'] }}</strong><span x-show="!showAmounts">•••</span></span>
                    <span style="color: #d97706;">تاییدنشده: <strong x-show="showAmounts">{{ $data['unverified_revenue'] }}</strong><span x-show="!showAmounts">•••</span></span>
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>

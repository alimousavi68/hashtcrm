<x-filament-widgets::widget>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
        
        <!-- ستون ۱: آخرین فیش‌های بانکی منتظر تایید -->
        <x-filament::section>
            <x-slot name="heading">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 18px; height: 18px; flex-shrink: 0; color: #d97706;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5A1.5 1.5 0 0 1 21 6v9a1.5 1.5 0 0 1-1.5 1.5H3.75A1.5 1.5 0 0 1 2.25 15V6a1.5 1.5 0 0 1 1.5-1.5Zm13.5 6a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Z" />
                        </svg>
                        <span style="font-size: 14px; font-weight: 700;">فیش‌های بانکی منتظر بررسی و تایید</span>
                    </div>
                </div>
            </x-slot>

            @php $payments = $this->getPendingPayments(); @endphp

            @if(empty($payments))
                <div style="padding: 16px; text-align: center; color: #64748b; font-size: 12px; background: #f8fafc; border-radius: 8px;">
                    ✅ فیش منتظر تاییدی وجود ندارد.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($payments as $p)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 12px;">
                            <div>
                                <div style="font-weight: 700; color: #0f172a;">{{ $p['project_title'] }}</div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $p['client_name'] }} | {{ $p['created_jalali'] }}</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-weight: 800; color: #d97706; background: #fffbeb; padding: 2px 8px; border-radius: 6px;">{{ $p['amount'] }} تومان</span>
                                <a href="{{ route('filament.admin.resources.projects.payments', ['record' => $p['project_id']]) }}" style="color: #4f46e5; font-weight: 700; text-decoration: none;">بررسی ←</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::section>

        <!-- ستون ۲: آخرین تیکت‌های پشتیبانی باز -->
        <x-filament::section>
            <x-slot name="heading">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 18px; height: 18px; color: #e11d48;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 5.97 5.97 0 0 1 1.057-2.038A8.25 8.25 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                        <span style="font-size: 14px; font-weight: 700;">تیکت‌های باز و نیازمند پاسخ</span>
                    </div>
                </div>
            </x-slot>

            @php $tickets = $this->getOpenTickets(); @endphp

            @if(empty($tickets))
                <div style="padding: 16px; text-align: center; color: #64748b; font-size: 12px; background: #f8fafc; border-radius: 8px;">
                    🎉 تیکت باز و در انتظار پاسخی وجود ندارد.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($tickets as $t)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 12px;">
                            <div>
                                <div style="font-weight: 700; color: #0f172a;">{{ $t['subject'] }}</div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $t['client_name'] }} | {{ $t['project_title'] }}</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 10px; font-weight: 700; background: #ffe4e6; color: #be123c; padding: 2px 6px; border-radius: 4px;">{{ $t['status_label'] }}</span>
                                <a href="{{ route('filament.admin.resources.tickets.index') }}" style="color: #4f46e5; font-weight: 700; text-decoration: none;">پاسخ ←</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::section>

    </div>
</x-filament-widgets::widget>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0; color: var(--primary-600, #4f46e5);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-1-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.25 2.25L15 7.5" />
                    </svg>
                    <span style="font-size: 15px; font-weight: 700; color: var(--gray-900, #0f172a);">پیشرفت تک‌تک پروژه‌های ناتمام و زمان باقیمانده</span>
                </div>

                <!-- دکمه‌های فیلتر سریع -->
                <div style="display: flex; align-items: center; gap: 6px; background: #f1f5f9; padding: 4px; border-radius: 10px;">
                    <button wire:click="setTab('all')" 
                            style="padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 7px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'all' ? 'background: #ffffff; color: #4f46e5; box-shadow: 0 1px 2px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                        همه پروژه‌ها
                    </button>
                    <button wire:click="setTab('overdue')" 
                            style="padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 7px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'overdue' ? 'background: #ffffff; color: #e11d48; box-shadow: 0 1px 2px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                        ⚠️ دارای تاخیر
                    </button>
                    <button wire:click="setTab('in_dev')" 
                            style="padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 7px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'in_dev' ? 'background: #ffffff; color: #0284c7; box-shadow: 0 1px 2px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                        🚀 در حال توسعه و بازنگری
                    </button>
                    <button wire:click="setTab('settled_pending')" 
                            style="padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 7px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'settled_pending' ? 'background: #ffffff; color: #d97706; box-shadow: 0 1px 2px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                        💳 تسویه‌نشده
                    </button>
                </div>
            </div>
        </x-slot>

        @php
            $projects = $this->getProjectsData();
        @endphp

        @if(empty($projects))
            <div style="padding: 24px; text-align: center; color: #64748b; font-size: 13px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                🎉 تمامی پروژه‌ها به اتمام رسیده و پروژه غیرفعال یا ناتمامی وجود ندارد.
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 8px;">
                @foreach($projects as $p)
                    <div style="background: var(--card-bg, #ffffff); border: 1px solid var(--gray-200, #e2e8f0); border-radius: 14px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        
                        <!-- هدر کارت -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <h3 style="font-size: 14px; font-weight: 900; color: #0f172a; margin: 0;">
                                    {{ $p['title'] }}
                                </h3>
                                <span style="padding: 2px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: #f1f5f9; color: #334155; white-space: nowrap;">
                                    {{ $p['status_label'] }}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 12px; color: #64748b;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0; color: #94a3b8;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>مشتری: {{ $p['client_name'] }}</span>
                            </div>
                        </div>

                        <!-- نوار پیشرفت و درصد باقیمانده -->
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; font-weight: 700;">
                                <span style="color: #4f46e5; display: flex; align-items: center; gap: 4px;">
                                    <span style="width: 7px; height: 7px; border-radius: 9999px; background: #4f46e5;"></span>
                                    پیشرفت: ٪{{ $p['progress_percent'] }}
                                </span>
                                <span style="color: #e11d48; display: flex; align-items: center; gap: 4px;">
                                    <span style="width: 7px; height: 7px; border-radius: 9999px; background: #f43f5e;"></span>
                                    کار باقیمانده: ٪{{ $p['remaining_percent'] }}
                                </span>
                            </div>

                            <!-- نوار گرافیکی -->
                            <div style="width: 100%; height: 10px; background: #ffe4e6; border-radius: 9999px; overflow: hidden; display: flex;">
                                <div style="width: {{ $p['progress_percent'] }}%; height: 100%; background: #4f46e5; transition: width 0.4s ease; border-radius: 9999px;"></div>
                            </div>
                        </div>

                        <!-- فوتر کارت: وضعیت ددلاین و کلید اقدام -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 8px; border-top: 1px solid #f1f5f9; font-size: 11px;">
                            <div>
                                @if($p['days_remaining'] !== null)
                                    @if($p['is_overdue'])
                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #be123c; font-weight: 700; background: #ffe4e6; padding: 2px 8px; border-radius: 6px;">
                                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $p['days_remaining'] }} روز تاخیر
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #b45309; font-weight: 700; background: #fef3c7; padding: 2px 8px; border-radius: 6px;">
                                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                            </svg>
                                            {{ $p['days_remaining'] }} روز باقیمانده
                                        </span>
                                    @endif
                                @else
                                    <span style="color: #94a3b8;">ددلاین ثبت‌نشده</span>
                                @endif
                            </div>

                            <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $p['id']]) }}" 
                               style="display: inline-flex; align-items: center; gap: 4px; font-weight: 800; color: #4f46e5; text-decoration: none; padding: 4px 8px; border-radius: 6px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s;">
                                <span>مدیریت پروژه</span>
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

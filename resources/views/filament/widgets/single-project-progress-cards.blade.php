<x-filament-widgets::widget>
    <style>
        .hasht-proj-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s ease;
        }
        .hasht-proj-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px -4px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }
        .hasht-manage-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 700;
            color: #4338ca;
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 6px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hasht-manage-btn:hover {
            background: #e0e7ff;
            border-color: #a5b4fc;
            box-shadow: 0 2px 6px rgba(67,56,202,0.15);
        }
        .hasht-manage-btn:hover svg {
            transform: translateX(-3px);
        }
        .hasht-manage-btn svg {
            transition: transform 0.2s ease;
        }
    </style>

    <x-filament::section>
        <x-slot name="heading">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0; color: var(--primary-600, #4f46e5);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-1-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.25 2.25L15 7.5" />
                    </svg>
                    <span style="font-size: 14px; font-weight: 600; color: var(--gray-900, #0f172a);">وضعیت و پیشرفت پروژه‌های در حال اجرا</span>
                </div>

                <!-- دکمه‌های فیلتر سریع -->
                <div style="display: flex; align-items: center; gap: 4px; background: #e2e8f0; padding: 4px; border-radius: 8px;">
                    <button wire:click="setTab('all')" 
                            style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'all' ? 'background: #4f46e5; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(79,70,229,0.3);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span>همه پروژه‌ها</span>
                    </button>
                    <button wire:click="setTab('overdue')" 
                            style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'overdue' ? 'background: #e11d48; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(225,29,72,0.3);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>دارای تاخیر</span>
                    </button>
                    <button wire:click="setTab('in_dev')" 
                            style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'in_dev' ? 'background: #0284c7; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(2,132,199,0.3);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.24A4.5 4.5 0 0 0 3 19.5v.75c0 .414.336.75.75.75h.75a4.5 4.5 0 0 0 4.5-4.5v-.75a.75.75 0 0 0-.75-.75h-.75Z" />
                        </svg>
                        <span>در حال توسعه و بازنگری</span>
                    </button>
                    <button wire:click="setTab('settled_pending')" 
                            style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'settled_pending' ? 'background: #d97706; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(217,119,6,0.3);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.753 3h15.75c.621 0 1.125-.504 1.125-1.125V18a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 2.25 18v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span>تسویه‌نشده</span>
                    </button>
                </div>
            </div>
        </x-slot>

        @php
            $projects = $this->getProjectsData();
        @endphp

        @if(empty($projects))
            <div style="padding: 20px; text-align: center; color: #475569; font-size: 12px; background: #f8fafc; border-radius: 10px; border: 1px dashed #e2e8f0;">
                🎉 تمامی پروژه‌ها به اتمام رسیده و پروژه غیرفعال یا ناتمامی وجود ندارد.
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin-top: 6px;">
                @foreach($projects as $p)
                    <div class="hasht-proj-card">
                        
                        <!-- هدر کارت -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">
                                    {{ $p['title'] }}
                                </h3>
                                <span style="padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: #f1f5f9; color: #334155; white-space: nowrap;">
                                    {{ $p['status_label'] }}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 11px; color: #475569; font-weight: 500;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>مشتری: <strong style="color: #0f172a;">{{ $p['client_name'] }}</strong></span>
                            </div>
                        </div>

                        <!-- نوار پیشرفت و درصد باقیمانده -->
                        <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; font-weight: 500;">
                                <span style="color: #047857; display: flex; align-items: center; gap: 4px;">
                                    <span style="width: 7px; height: 7px; border-radius: 9999px; background: #10b981;"></span>
                                    پیشرفت: ٪<strong style="font-weight: 700; color: #065f46;">{{ $p['progress_percent'] }}</strong>
                                </span>
                                <span style="color: #475569; display: flex; align-items: center; gap: 4px;">
                                    <span style="width: 7px; height: 7px; border-radius: 9999px; background: #64748b;"></span>
                                    کار باقیمانده: ٪<strong style="font-weight: 700; color: #334155;">{{ $p['remaining_percent'] }}</strong>
                                </span>
                            </div>

                            <!-- نوار گرافیکی (سبز انجام شده + طوسی باقیمانده) -->
                            <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; display: flex;">
                                <div style="width: {{ $p['progress_percent'] }}%; height: 100%; background: #10b981; transition: width 0.4s ease; border-radius: 9999px;"></div>
                            </div>
                        </div>

                        <!-- فوتر کارت: وضعیت ددلاین و کلید اقدام -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 8px; border-top: 1px solid #f1f5f9; font-size: 11px;">
                            <div>
                                @if($p['days_remaining'] !== null)
                                    @if($p['is_overdue'])
                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #be123c; font-weight: 700; background: #ffe4e6; padding: 3px 8px; border-radius: 6px;">
                                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $p['days_remaining'] }} روز تاخیر
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #b45309; font-weight: 700; background: #fef3c7; padding: 3px 8px; border-radius: 6px;">
                                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                            </svg>
                                            {{ $p['days_remaining'] }} روز باقیمانده
                                        </span>
                                    @endif
                                @else
                                    <span style="color: #64748b; font-weight: 500; background: #f8fafc; padding: 3px 8px; border-radius: 6px;">ددلاین ثبت‌نشده</span>
                                @endif
                            </div>

                            <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $p['id']]) }}" 
                               aria-label="مدیریت پروژه {{ $p['title'] }}"
                               class="hasht-manage-btn">
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

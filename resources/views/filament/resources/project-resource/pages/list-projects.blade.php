<x-filament-panels::page>

    <!-- 1. کادر فیلترها و جستجوی سریع -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            
            <!-- تب‌های فیلتر -->
            <div style="display: flex; align-items: center; gap: 4px; background: #f1f5f9; padding: 4px; border-radius: 8px; flex-wrap: wrap;">
                
                <button wire:click="setTab('leads')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'leads' ? 'background: #f59e0b; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(245,158,11,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.974 0-5.699-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    <span>سرنخ‌های جدید</span>
                </button>

                <button wire:click="setTab('all')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'all' ? 'background: #4f46e5; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(79,70,229,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span>همه پروژه‌ها</span>
                </button>

                <button wire:click="setTab('in_progress')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'in_progress' ? 'background: #0284c7; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(2,132,199,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                    </svg>
                    <span>در حال طراحی و توسعه</span>
                </button>

                <button wire:click="setTab('brief_contract')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'brief_contract' ? 'background: #d97706; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(217,119,6,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>بریف و قرارداد</span>
                </button>

                <button wire:click="setTab('overdue')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'overdue' ? 'background: #e11d48; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(225,29,72,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <span>دارای تاخیر</span>
                </button>

                <button wire:click="setTab('unsettled')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'unsettled' ? 'background: #b45309; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(180,83,9,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>تسویه‌نشده</span>
                </button>

                <button wire:click="setTab('completed')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'completed' ? 'background: #15803d; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(21,128,61,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>تکمیل‌شده</span>
                </button>

                <button wire:click="setTab('archived')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'archived' ? 'background: #64748b; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(100,116,139,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.5h3m-6 3h6m-9-7.5h16.5m-16.5 0A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v1.5" />
                    </svg>
                    <span>بایگانی / لغوشده</span>
                </button>
            </div>

            <!-- کادر جستجو -->
            <div style="position: relative; min-width: 240px;">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="جستجو در عنوان پروژه یا مشتری..." 
                       style="width: 100%; padding: 6px 36px 6px 12px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; color: #0f172a; outline: none; transition: border-color 0.2s;" />
                <svg style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>

        </div>
    </div>

    <!-- 3. شبکه‌بندی مستقل کارت‌های پروژه‌ها (بدون کادر جدول!) -->
    @php
        $projects = $this->getProjectsData();
    @endphp

    @if(empty($projects))
        <div style="padding: 32px 16px; text-align: center; color: #475569; font-size: 13px; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <svg style="width: 32px; height: 32px; margin: 0 auto 8px auto; color: #94a3b8;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.5h3m-6 3h6m-9-7.5h16.5m-16.5 0A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v1.5" />
            </svg>
            <span>هیچ پروژه‌ای مطابق فیلتر یا جستجوی جاری یافت نشد.</span>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
            @foreach($projects as $p)
                @php
                    $badgeBg = match($p['status']) {
                        'draft' => '#f1f5f9',
                        'brief' => '#fef3c7',
                        'contract' => '#e0f2fe',
                        'in_progress' => '#eef2ff',
                        'review' => '#ffe4e6',
                        'ready_handover' => '#ccfbf1',
                        'completed' => '#dcfce7',
                        default => '#f1f5f9',
                    };

                    $badgeText = match($p['status']) {
                        'draft' => '#334155',
                        'brief' => '#92400e',
                        'contract' => '#0369a1',
                        'in_progress' => '#4338ca',
                        'review' => '#be123c',
                        'ready_handover' => '#0f766e',
                        'completed' => '#166534',
                        default => '#334155',
                    };
                @endphp

                <!-- کارت مجزای پروژه -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); transition: all 0.2s;">
                    
                    <!-- هدر کارت -->
                    <div>
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
                            <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.4;">
                                {{ $p['title'] }}
                            </h3>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                <span style="padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: {{ $badgeBg }}; color: {{ $badgeText }}; white-space: nowrap;">
                                    {{ $p['status_label'] }}
                                </span>
                                @if($p['is_settled'])
                                    <span style="padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 4px; background: #dcfce7; color: #166534; white-space: nowrap; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg style="width: 10px; height: 10px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        تسویه‌شده
                                    </span>
                                @else
                                    <span style="padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 4px; background: #fffbeb; color: #b45309; white-space: nowrap; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg style="width: 10px; height: 10px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                        </svg>
                                        تسویه‌نشده
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- کارفرما -->
                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 8px; font-size: 11px; color: #475569; font-weight: 500;">
                            <svg style="width: 14px; height: 14px; flex-shrink: 0; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>کارفرما: <strong style="color: #0f172a;">{{ $p['client_name'] }}</strong></span>
                        </div>
                    </div>

                    <!-- نوار پیشرفت و کار باقیمانده -->
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

                        <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; display: flex;">
                            <div style="width: {{ $p['raw_progress'] }}%; height: 100%; background: #10b981; transition: width 0.4s ease; border-radius: 9999px;"></div>
                        </div>
                    </div>

                    <!-- آیکون‌های میانبر زیرماژول‌های پروژه (با آیکون‌های بومی Heroicons، بدون ایموجی!) -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; background: #f8fafc; padding: 6px; border-radius: 8px; border: 1px solid #f1f5f9; font-size: 10px;">
                        <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $p['id']]) }}" title="پرسشنامه نیازمندی‌ها" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #0284c7;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>پرسشنامه</span>
                        </a>

                        <a href="{{ route('filament.admin.resources.projects.contract', ['record' => $p['id']]) }}" title="قرارداد و امضا" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #16a34a;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>قرارداد</span>
                        </a>

                        <a href="{{ route('filament.admin.resources.projects.payments', ['record' => $p['id']]) }}" title="پرداخت‌ها و فیش‌ها" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #d97706;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.753 3h15.75c.621 0 1.125-.504 1.125-1.125V18a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 2.25 18v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            <span>مالی</span>
                        </a>

                        <a href="{{ route('filament.admin.resources.projects.vault', ['record' => $p['id']]) }}" title="گاوصندوق امن" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #4338ca;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <span>گاوصندوق</span>
                        </a>

                        <a href="{{ route('filament.admin.resources.projects.handover', ['record' => $p['id']]) }}" title="بسته تحویل" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #0f766e;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                            <span>تحویل</span>
                        </a>

                        <a href="{{ route('filament.admin.resources.projects.tickets', ['record' => $p['id']]) }}" title="تیکت‌های پشتیبانی" style="display: inline-flex; align-items: center; gap: 4px; color: #334155; font-weight: 600; text-decoration: none; padding: 4px 6px; border-radius: 4px; background: #ffffff; border: 1px solid #e2e8f0;">
                            <svg style="width: 12px; height: 12px; color: #e11d48;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 5.97 5.97 0 0 1 1.057-2.038A8.25 8.25 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>
                            <span>تیکت‌ها</span>
                        </a>
                    </div>

                    <!-- فوتر کارت: وضعیت ددلاین و کلید اقدام اصلی -->
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

                        <div style="display: flex; align-items: center; gap: 6px;">
                            @if($p['demo_url'])
                                <a href="{{ $p['demo_url'] }}" target="_blank" title="مشاهده دمو آنلاین" style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #0369a1; text-decoration: none; padding: 5px 10px; border-radius: 6px; background: #f0f9ff; border: 1px solid #bae6fd;">
                                    <svg style="width: 13px; height: 13px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                    <span>دمو</span>
                                </a>
                            @endif
                            <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $p['id']]) }}" 
                               aria-label="مدیریت پروژه {{ $p['title'] }}"
                               style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 6px 12px; border-radius: 6px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s;">
                                <span>مدیریت پروژه</span>
                                <svg style="width: 13px; height: 13px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <x-filament-actions::modals />
</x-filament-panels::page>

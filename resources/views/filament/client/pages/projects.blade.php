<x-filament-panels::page>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        .hasht-client-container { display: flex; flex-direction: column; gap: 20px; font-family: 'Vazirmatn', sans-serif !important; direction: rtl; }
        .hasht-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
        .hasht-sec-title { font-size: 14px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }

        .hasht-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }
        .hasht-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .hasht-proj-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }
        .hasht-proj-card:hover { transform: translateY(-2px); border-color: #cbd5e1; box-shadow: 0 6px 14px rgba(0,0,0,0.05); }

        .hasht-banner { position: relative; overflow: hidden; border-radius: 12px; padding: 16px 20px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .hasht-banner-amber { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .hasht-banner-indigo { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
        .hasht-banner-blue { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
        .hasht-banner-purple { background: #faf5ff; border-color: #e9d5ff; color: #6b21a8; }
        .hasht-banner-emerald { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .hasht-banner-sky { background: #f0f9ff; border-color: #bae6fd; color: #075985; }
        .hasht-banner-gray { background: #f8fafc; border-color: #e2e8f0; color: #334155; }

        .hasht-grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px; }
        .hasht-grid-7 { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
        @media (max-width: 768px) {
            .hasht-grid-7 span { display: none; }
        }

        .hasht-manage-btn { display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 5px 12px; border-radius: 6px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s; font-size: 11px; cursor: pointer; }
        .hasht-manage-btn:hover { background: #e0e7ff; border-color: #a5b4fc; box-shadow: 0 2px 6px rgba(67,56,202,0.12); }
        .hasht-manage-btn:hover svg { transform: translateX(-3px); }
        .hasht-manage-btn svg { transition: transform 0.2s ease; }

        .hasht-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #f1f5f9; color: #334155; white-space: nowrap; }
        .hasht-badge-amber { background: #fef3c7; color: #92400e; }
        .hasht-badge-green { background: #dcfce7; color: #15803d; }

        .hasht-progress-track { width: 100%; background: #e2e8f0; border-radius: 9999px; height: 7px; overflow: hidden; }
        .hasht-progress-fill { background: #10b981; height: 100%; border-radius: 9999px; transition: width 0.5s ease; }

        .hasht-tab-bar { display: flex; border-bottom: 1px solid #e2e8f0; gap: 4px; overflow-x: auto; margin-top: 4px; }
        .hasht-tab-btn { padding: 8px 16px; font-size: 12px; font-weight: 700; border: none; background: transparent; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; display: inline-flex; align-items: center; gap: 6px; color: #64748b; transition: all 0.2s; white-space: nowrap; }
        .hasht-tab-btn-active { color: #4f46e5; border-bottom-color: #4f46e5; font-weight: 800; }
        .hasht-tab-btn:hover:not(.hasht-tab-btn-active) { color: #0f172a; }

        .custom-input { width: 100%; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 12px; outline: none; transition: border-color 0.2s; }
        .custom-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }

        svg { flex-shrink: 0; }
    </style>

    <div class="hasht-client-container">
        @if(!$selectedProjectId)
            <!-- STATE A: Projects Overview Grid (Admin Widget Card Style) -->
            <div class="hasht-card">
                <div class="hasht-sec-heading">
                    <div class="hasht-sec-title">
                        <svg style="width: 18px; height: 18px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>وضعیت و پیشرفت پروژه‌های شما</span>
                    </div>
                </div>

                @if(count($projects) > 0)
                    <div class="hasht-grid-2">
                        @foreach($projects as $p)
                            @php
                                $statusInfo = $statuses[$p->status] ?? ['label' => 'نامشخص', 'percent' => 0];
                                $remainingPercent = 100 - $statusInfo['percent'];
                            @endphp
                            <div class="hasht-proj-card">
                                <!-- هدر کارت پروژه -->
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                        <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">
                                            {{ $p->title }}
                                        </h3>
                                        <span class="hasht-badge">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                        تاریخ ثبت: {{ \App\Helpers\JalaliHelper::toJalali($p->created_at, 'Y/m/d') }}
                                    </div>
                                </div>

                                <!-- نوار پیشرفت دو رنگ -->
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 600;">
                                        <span style="color: #10b981; display: flex; align-items: center; gap: 4px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                                            پیشرفت: {{ \App\Helpers\JalaliHelper::toPersianDigits($statusInfo['percent']) }}٪
                                        </span>
                                        <span style="color: #64748b; display: flex; align-items: center; gap: 4px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span>
                                            کار باقیمانده: {{ \App\Helpers\JalaliHelper::toPersianDigits($remainingPercent) }}٪
                                        </span>
                                    </div>
                                    <div class="hasht-progress-track">
                                        <div class="hasht-progress-fill" style="width: {{ $statusInfo['percent'] }}%"></div>
                                    </div>
                                </div>

                                <!-- بخش اکشن پایین کارت -->
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; padding-top: 4px; border-top: 1px solid #f1f5f9;">
                                    <button wire:click="selectProject({{ $p->id }})" class="hasht-manage-btn">
                                        <span>مدیریت پروژه</span>
                                        <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                    </button>

                                    @if($p->status === 'review' && $p->feedback_deadline)
                                        <span class="hasht-badge hasht-badge-amber">
                                            ⏱ مهلت ثبت نظر
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 32px; color: #64748b; font-size: 12px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                        هیچ پروژه‌ای برای حساب کاربری شما یافت نشد.
                    </div>
                @endif
            </div>

        @else
            <!-- STATE B: Selected Project Workspace -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                <button wire:click="backToProjects" class="hasht-manage-btn" style="background: #f1f5f9; border-color: #cbd5e1; color: #334155;">
                    <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>بازگشت به لیست پروژه‌ها</span>
                </button>
                <div style="font-size: 12px; color: #64748b;">پروژه فعال: <strong style="color: #0f172a;">{{ $project->title }}</strong></div>
            </div>

            <!-- HERO NEXT ACTION BANNER -->
            @php $nextAction = $this->nextAction; @endphp
            @if($nextAction)
                @php
                    $bannerClass = 'hasht-banner-' . ($nextAction['color'] ?? 'indigo');
                @endphp
                <div class="hasht-banner {{ $bannerClass }}">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="padding: 8px; background: #ffffff; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <svg style="width: 20px; height: 20px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="hasht-badge" style="background: #ffffff; border: 1px solid #cbd5e1;">{{ $nextAction['badge'] }}</span>
                                <h3 style="font-size: 13px; font-weight: 700; margin: 0;">{{ $nextAction['title'] }}</h3>
                            </div>
                            <p style="font-size: 11px; margin-top: 2px; opacity: 0.85; line-height: 1.5;">
                                {{ $nextAction['description'] }}
                            </p>
                        </div>
                    </div>

                    @if($nextAction['buttonText'])
                        <div>
                            @if($nextAction['actionType'] === 'url')
                                <a href="{{ $nextAction['url'] }}" class="hasht-manage-btn">
                                    <span>{{ $nextAction['buttonText'] }}</span>
                                    <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </a>
                            @elseif($nextAction['actionType'] === 'tab')
                                <button wire:click="setActiveTab('{{ $nextAction['tab'] }}')" class="hasht-manage-btn">
                                    <span>{{ $nextAction['buttonText'] }}</span>
                                    <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- PASTEL PROGRESS HEADER & 7-PHASE STEPPER -->
            <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="padding: 2px 6px; font-size: 10px; font-weight: 700; background: #eef2ff; color: #4338ca; border-radius: 4px; border: 1px solid #c7d2fe;">میزکار پروژه</span>
                            <span style="font-size: 11px; color: #64748b;">شناسه: #{{ $project->id }}</span>
                        </div>
                        <h2 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $project->title }}</h2>
                    </div>
                    
                    <div>
                        <span class="hasht-badge" style="font-size: 11px; padding: 4px 10px; background: #f1f5f9; border: 1px solid #cbd5e1;">
                            وضعیت: {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Progress Bar & Stepper -->
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @php $remaining = 100 - $progressPercent; @endphp
                    <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 600;">
                        <span style="color: #10b981; display: flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            پیشرفت: {{ \App\Helpers\JalaliHelper::toPersianDigits($progressPercent) }}٪
                        </span>
                        <span style="color: #64748b; display: flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span>
                            کار باقیمانده: {{ \App\Helpers\JalaliHelper::toPersianDigits($remaining) }}٪
                        </span>
                    </div>
                    
                    <div class="hasht-progress-track">
                        <div class="hasht-progress-fill" style="width: {{ $progressPercent }}%"></div>
                    </div>

                    <!-- 7-Phase Interactive Micro Dots -->
                    <div class="hasht-grid-7" style="margin-top: 4px;">
                        @foreach($statuses as $key => $info)
                            @php
                                $isCurrent = $project->status === $key;
                                $isPassed = $progressPercent >= $info['percent'];
                            @endphp
                            <div wire:click="setActiveTab('roadmap')" style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 4px;" title="{{ $info['label'] }}">
                                <div style="width: 100%; height: 5px; border-radius: 9999px; transition: all 0.3s; {{ $isCurrent ? 'background: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe;' : ($isPassed ? 'background: #10b981;' : 'background: #cbd5e1;') }}"></div>
                                <span style="font-size: 9px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; {{ $isCurrent ? 'font-weight: 800; color: #4f46e5;' : ($isPassed ? 'color: #334155;' : 'color: #94a3b8;') }}">
                                    {{ $info['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Workspace Navigation Tabs -->
            <div class="hasht-tab-bar">
                <button wire:click="setActiveTab('roadmap')" class="hasht-tab-btn {{ $activeTab === 'roadmap' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 012.447 15.5V5.5a2 2 0 011.106-1.789L9 1m0 19v-9m0 9l5.447-2.724A2 2 0 0019.553 15.5V5.5a2 2 0 00-1.106-1.789L15 1m-6 9V1m0 9l6-3.333"/></svg>
                    <span>نقشه راه فازها</span>
                </button>
                <button wire:click="setActiveTab('finance')" class="hasht-tab-btn {{ $activeTab === 'finance' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>قرارداد و امور مالی</span>
                </button>
                <button wire:click="setActiveTab('brief')" class="hasht-tab-btn {{ $activeTab === 'brief' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>بریف و دسترسی‌ها</span>
                </button>
                <button wire:click="setActiveTab('demo')" class="hasht-tab-btn {{ $activeTab === 'demo' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>بازنگری دمو</span>
                </button>
                <button wire:click="setActiveTab('handover')" class="hasht-tab-btn {{ $activeTab === 'handover' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0h-4m0 0v13m0 0h12"/></svg>
                    <span>بسته تحویل نهایی</span>
                </button>
            </div>

            <!-- Tab Content -->
            <div style="margin-top: 4px;">
                @if($activeTab === 'roadmap')
                    <!-- ROADMAP TAB -->
                    <div class="hasht-card">
                        <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">مراحل اجرای پروژه</h3>
                        
                        <div style="display: flex; flex-direction: column;">
                            @foreach($statuses as $key => $info)
                                @php
                                    $isCurrent = $project->status === $key;
                                    $isPassed = $progressPercent >= $info['percent'];
                                @endphp
                                <div style="display: flex; gap: 14px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; border: 2px solid; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; {{ $isCurrent ? 'background: #4f46e5; border-color: #4f46e5; color: #ffffff; box-shadow: 0 0 0 3px #eef2ff;' : ($isPassed ? 'background: #10b981; border-color: #10b981; color: #ffffff;' : 'background: #ffffff; border-color: #cbd5e1; color: #94a3b8;') }}">
                                            @if($isPassed && !$isCurrent)
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <span>{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        @if(!$loop->last)
                                            <div style="width: 2px; flex-grow: 1; background: #e2e8f0; margin: 4px 0; min-height: 26px;"></div>
                                        @endif
                                    </div>

                                    <div style="padding-bottom: 20px; padding-top: 3px; flex-grow: 1;">
                                        <h4 style="font-size: 13px; font-weight: 700; margin: 0; {{ $isCurrent ? 'color: #4f46e5;' : ($isPassed ? 'color: #0f172a;' : 'color: #94a3b8;') }}">
                                            {{ $info['label'] }}
                                        </h4>
                                        @if($isCurrent)
                                            <p style="font-size: 11px; color: #64748b; margin-top: 4px; line-height: 1.5;">
                                                پروژه شما هم‌اکنون در این مرحله قرار دارد. اقدامات مربوط به امور مالی، بریف و دمو را می‌توانید از تب‌های بالای صفحه پیگیری کنید.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif($activeTab === 'finance')
                    <!-- FINANCE TAB -->
                    <div class="hasht-grid-2">
                        <!-- Contract Card -->
                        <div class="hasht-card" style="display: flex; flex-direction: column; gap: 14px;">
                            <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin: 0;">
                                <svg style="width: 16px; height: 16px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>قرارداد همکاری پروژه</span>
                            </h3>

                            @if($project->contract)
                                @if($project->contract->signed_at)
                                    <div style="padding: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; font-size: 11px; display: flex; flex-direction: column; gap: 4px;">
                                        <div style="display: flex; align-items: center; gap: 4px; font-weight: 700; font-size: 12px;">
                                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>قرارداد با موفقیت امضای دیجیتال شده است</span>
                                        </div>
                                        <p style="margin: 0;">امضاکننده: {{ $project->contract->signature_name }} | کد ملی: {{ $project->contract->signature_national_code }}</p>
                                        <p style="margin: 0;">تاریخ امضا: {{ \App\Helpers\JalaliHelper::toJalali($project->contract->signed_at, 'Y/m/d H:i') }}</p>
                                    </div>
                                    <div style="padding: 14px; background: #f8fafc; border-radius: 8px; max-height: 300px; overflow-y: auto; font-size: 11px; color: #334155; line-height: 1.7; border: 1px solid #e2e8f0;">
                                        {!! $renderedContractContent !!}
                                    </div>
                                @else
                                    <div style="padding: 14px; background: #f8fafc; border-radius: 8px; max-height: 300px; overflow-y: auto; font-size: 11px; color: #334155; line-height: 1.7; border: 1px solid #e2e8f0;">
                                        {!! $renderedContractContent !!}
                                    </div>
                                    
                                    <form wire:submit.prevent="signContract" style="display: flex; flex-direction: column; gap: 12px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                        <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">امضای دیجیتال قرارداد</h4>
                                        <div class="hasht-grid-2">
                                            <div>
                                                <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 4px;">نام و نام خانوادگی امضاکننده</label>
                                                <input type="text" wire:model="sigName" placeholder="مثال: علی رضایی" class="custom-input" required>
                                                @error('sigName') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 4px;">کد ملی ده رقمی</label>
                                                <input type="text" wire:model="sigNationalCode" maxlength="10" placeholder="مثال: 0012345678" class="custom-input" required>
                                                @error('sigNationalCode') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="hasht-manage-btn" style="width: 100%; justify-content: center; padding: 8px;">
                                            <span>امضا و پذیرش تعهدات قرارداد</span>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p style="font-size: 11px; color: #64748b; text-align: center; padding: 20px;">قرارداد همکاری پروژه به زودی توسط مدیریت بارگذاری خواهد شد.</p>
                            @endif
                        </div>

                        <!-- Finance Card -->
                        <div class="hasht-card" style="display: flex; flex-direction: column; gap: 14px;">
                            <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin: 0;">
                                <svg style="width: 16px; height: 16px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>حسابداری و ثبت فیش‌های بانکی</span>
                            </h3>

                            <div style="padding: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 11px; color: #1e40af; display: flex; flex-direction: column; gap: 3px;">
                                <p style="font-weight: 700; margin: 0;">اطلاعات حساب جهت واریز پیش‌پرداخت / فاکتورها:</p>
                                <p style="margin: 0;">شماره کارت: ۵۰۲۲-۲۹۱۰-۱۲۳۴-۵۶۷۸</p>
                                <p style="margin: 0;">به نام: مدیریت شرکت هشت</p>
                                <p style="opacity: 0.8; font-size: 10px; margin-top: 2px;">پس از واریز مبلغ، تصویر فیش واریزی را جهت تایید و فعال‌سازی مراحل پروژه در فرم زیر ثبت کنید.</p>
                            </div>

                            @if($project->status === 'contract')
                                <form wire:submit.prevent="uploadSlip" style="display: flex; flex-direction: column; gap: 12px; padding-top: 4px;">
                                    <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">ثبت فیش واریز جدید</h4>
                                    <div class="hasht-grid-2">
                                        <div>
                                            <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 4px;">مبلغ واریزی (تومان)</label>
                                            <input type="number" wire:model="paymentAmount" placeholder="مثال: 5000000" class="custom-input" required>
                                            @error('paymentAmount') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 4px;">تصویر فیش بانکی</label>
                                            <input type="file" wire:model="bankSlipFile" style="font-size: 11px; color: #64748b;" required>
                                            @error('bankSlipFile') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="hasht-manage-btn" style="width: 100%; justify-content: center; padding: 8px;">
                                        <span>ارسال و ثبت فیش پرداخت</span>
                                    </button>
                                </form>
                            @endif

                            <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 4px;">
                                <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">سوابق تراکنش‌ها</h4>
                                @if($project->payments->count() > 0)
                                    <div style="overflow-x: auto;">
                                        <table style="width: 100%; font-size: 11px; text-align: right; border-collapse: collapse;">
                                            <thead>
                                                <tr style="background: #f8fafc; color: #475569; border-bottom: 1px solid #e2e8f0;">
                                                    <th style="padding: 6px 10px;">مبلغ</th>
                                                    <th style="padding: 6px 10px;">وضعیت بررسی</th>
                                                    <th style="padding: 6px 10px;">تاریخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($project->payments as $payment)
                                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                                        <td style="padding: 6px 10px; font-weight: 700; color: #0f172a;">
                                                            {{ number_format($payment->amount) }} تومان
                                                        </td>
                                                        <td style="padding: 6px 10px;">
                                                            @if($payment->status === 'approved')
                                                                <span style="padding: 2px 6px; font-size: 10px; font-weight: 700; background: #f0fdf4; color: #166534; border-radius: 4px;">تایید شده</span>
                                                            @elseif($payment->status === 'rejected')
                                                                <span style="padding: 2px 6px; font-size: 10px; font-weight: 700; background: #fef2f2; color: #991b1b; border-radius: 4px;">رد شده</span>
                                                            @else
                                                                <span style="padding: 2px 6px; font-size: 10px; font-weight: 700; background: #fffbeb; color: #92400e; border-radius: 4px;">در انتظار بررسی</span>
                                                            @endif
                                                        </td>
                                                        <td style="padding: 6px 10px; font-size: 10px; color: #94a3b8;">
                                                            {{ \App\Helpers\JalaliHelper::toJalali($payment->created_at, 'Y/m/d') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p style="font-size: 11px; color: #64748b; text-align: center; padding: 12px;">تراکنشی یافت نشد.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'brief')
                    <!-- BRIEF TAB -->
                    <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                        @if($project->status === 'brief')
                            <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">تکمیل پرسشنامه بریف پروژه</h3>
                            <p style="font-size: 11px; color: #64748b; margin: 0;">لطفاً مشخصات کسب‌وکار و اطلاعات دسترسی هاست/دامنه خود را به صورت گام به گام تکمیل فرمایید تا فرآیند فنی کار آغاز شود.</p>
                            
                            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">تکمیل بریف در صفحه اختصاصی</h4>
                                <p style="font-size: 11px; color: #475569; max-width: 400px; line-height: 1.5; margin: 0;">برای راحتی شما و تمرکز بیشتر، فرم بریف پروژه شما در یک صفحه اختصاصی آماده شده است. لطفاً برای شروع فرآیند تکمیل اطلاعات روی دکمه زیر کلیک کنید.</p>
                                <a href="{{ \App\Filament\Client\Pages\CompleteBrief::getUrl() }}" class="hasht-manage-btn" style="padding: 8px 18px;">
                                    <span>ورود به فرم بریف</span>
                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </a>
                            </div>
                        @else
                            <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin: 0;">اطلاعات بریف و دسترسی‌های ثبت‌شده</h3>
                            @if($project->briefAnswer)
                                <div style="background: #f8fafc; border-radius: 8px; padding: 14px; font-size: 11px; line-height: 1.7; border: 1px solid #e2e8f0;">
                                    @if(is_array($project->briefAnswer->dynamic_answers))
                                        <div class="hasht-grid-2">
                                            @foreach($project->briefAnswer->dynamic_answers as $key => $value)
                                                @if(!is_array($value) && !empty($value))
                                                    <p style="margin: 0;"><strong style="color: #64748b;">{{ __($key) }}:</strong> {{ $value }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p style="margin: 0;">اطلاعاتی یافت نشد.</p>
                                    @endif
                                </div>
                            @else
                                <p style="font-size: 11px; color: #64748b; text-align: center; padding: 16px;">پرسشنامه بریف برای این پروژه هنوز تکمیل نشده است.</p>
                            @endif

                            @if($project->credential)
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px; margin-top: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px; margin: 0;">
                                            <svg style="width: 16px; height: 16px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <span>گاوصندوق دسترسی‌های هاست و دامنه (رمزنگاری‌شده)</span>
                                        </h4>
                                        <span style="padding: 2px 6px; font-size: 10px; font-weight: 700; background: #f0fdf4; color: #166534; border-radius: 4px;">محفوظ و ایمن</span>
                                    </div>
                                    
                                    <div class="hasht-grid-2" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                                        <div style="padding: 10px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <span style="font-size: 10px; color: #64748b; display: block;">ارائه‌دهنده هاست:</span>
                                            <span style="font-weight: 700; color: #0f172a; margin-top: 2px; display: block; font-size: 11px;">{{ $project->credential->host_provider ?: 'ثبت نشده' }}</span>
                                        </div>

                                        <div x-data="{ copied: false }" style="padding: 10px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                                            <div>
                                                <span style="font-size: 10px; color: #64748b; display: block;">آدرس پنل هاست:</span>
                                                <span style="font-weight: 700; color: #0f172a; margin-top: 2px; display: block; direction: ltr; text-align: right; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 11px;">{{ $project->credential->host_panel_url ?: 'ثبت نشده' }}</span>
                                            </div>
                                            @if($project->credential->host_panel_url)
                                                <button type="button" x-on:click="navigator.clipboard.writeText('{{ $project->credential->host_panel_url }}'); copied = true; setTimeout(() => copied = false, 2000)" style="padding: 4px; border-radius: 6px; background: #f1f5f9; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.2s;">
                                                    <template x-if="!copied">
                                                        <svg style="width: 14px; height: 14px; color: #475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    </template>
                                                    <template x-if="copied">
                                                        <svg style="width: 14px; height: 14px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                </button>
                                            @endif
                                        </div>

                                        <div style="padding: 10px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <span style="font-size: 10px; color: #64748b; display: block;">ثبت‌کننده دامنه:</span>
                                            <span style="font-weight: 700; color: #0f172a; margin-top: 2px; display: block; font-size: 11px;">{{ $project->credential->domain_provider ?: 'ثبت نشده' }}</span>
                                        </div>
                                    </div>

                                    <p style="font-size: 10px; color: #92400e; font-weight: 500; display: flex; align-items: center; gap: 4px; margin: 0;">
                                        <svg style="width: 13px; height: 13px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>نکته امنیتی: کلمات عبور به صورت رمزنگاری دوطرفه ذخیره شده و صرفاً جهت اجرای پروژه توسط تیم توسعه قابل بهره‌برداری هستند.</span>
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                @elseif($activeTab === 'demo')
                    <!-- DEMO TAB -->
                    <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                            <div>
                                <h3 style="font-size: 13px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px; margin: 0;">
                                    <svg style="width: 16px; height: 16px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>بررسی دمو و پیش‌نمایش پروژه</span>
                                </h3>
                                <p style="font-size: 11px; color: #64748b; margin-top: 2px;">نسخه آزمایشی پروژه خود را بررسی کنید و نظرات خود را برای اصلاح یا تایید نهایی بنویسید.</p>
                            </div>
                        </div>

                        @if($project->status !== 'review' && $project->feedbacks->count() === 0)
                            <div style="padding: 24px; background: #f8fafc; border-radius: 8px; text-align: center; border: 1px dashed #cbd5e1;">
                                <p style="font-size: 11px; color: #64748b; margin: 0;">پروژه در حال حاضر در فاز دمو و بازنگری نیست. به محض اتمام توسعه و انتشار دمو توسط تیم فنی، این بخش فعال خواهد شد.</p>
                            </div>
                        @else
                            @if(!$project->demo_url)
                                <div style="padding: 20px; background: #f8fafc; border-radius: 8px; text-align: center; border: 1px dashed #cbd5e1;">
                                    <p style="font-size: 11px; color: #64748b; margin: 0;">دموی اولیه پروژه در حال آماده‌سازی است. به محض قرار گرفتن لینک دمو، از طریق همین بخش می‌توانید آن را بررسی کنید.</p>
                                </div>
                            @else
                                <!-- Demo URL Button -->
                                <div style="display: flex; justify-content: center; padding: 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <a href="{{ $project->demo_url }}" target="_blank" class="hasht-manage-btn" style="padding: 8px 18px; font-size: 12px;">
                                        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>مشاهده دمو و پیش‌نمایش زنده سایت</span>
                                    </a>
                                </div>

                                @if($project->status === 'review')
                                    <!-- Feedback Form -->
                                    <div style="display: flex; flex-direction: column; gap: 10px;">
                                        <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">ثبت نظر یا اعلام مغایرت‌ها:</h4>
                                        <textarea wire:model="feedbackNotes" placeholder="در صورتی که بخش‌های نیاز به اصلاح وجود دارد، جزئیات آن را در این قسمت به صورت گام به گام وارد نمایید..." rows="3" class="custom-input"></textarea>
                                        @error('feedbackNotes') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror

                                        <div class="hasht-grid-2">
                                            <button wire:click="submitFeedback('needs_changes')" class="hasht-manage-btn" style="background: #fef2f2; border-color: #fca5a5; color: #991b1b; justify-content: center;">
                                                <span>ثبت نیاز به اصلاحات و تغییرات</span>
                                            </button>
                                            <button wire:click="submitFeedback('approved')" class="hasht-manage-btn-green" style="justify-content: center;">
                                                <span>تایید نهایی دمو و ارسال به مرحله بعد</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- History of Feedbacks -->
                                <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                    <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">تاریخچه بازخوردها</h4>
                                    @forelse($project->feedbacks as $fb)
                                        <div style="padding: 10px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 11px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                                <span style="font-weight: 700;">
                                                    وضعیت ثبت شده: 
                                                    @if($fb->status === 'approved')
                                                        <span style="color: #16a34a;">تایید نهایی</span>
                                                    @elseif($fb->status === 'needs_changes')
                                                        <span style="color: #dc2626;">نیاز به تغییرات</span>
                                                    @else
                                                        <span style="color: #d97706;">در انتظار بررسی</span>
                                                    @endif
                                                </span>
                                                <span style="font-size: 10px; color: #94a3b8;">{{ \App\Helpers\JalaliHelper::toJalali($fb->created_at, 'Y/m/d H:i') }}</span>
                                            </div>
                                            <p style="color: #334155; line-height: 1.5; margin: 0;">{{ $fb->notes }}</p>
                                        </div>
                                    @empty
                                        <p style="font-size: 11px; color: #64748b; text-align: center; padding: 6px;">هیچ فیدبکی تاکنون ثبت نشده است.</p>
                                    @endforelse
                                </div>
                            @endif
                        @endif
                    </div>

                @elseif($activeTab === 'handover')
                    <!-- HANDOVER TAB -->
                    <div class="hasht-card">
                        @if(!$project->is_settled)
                            <!-- Settle Lock Screen -->
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 36px; text-align: center; gap: 12px;">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">بسته تحویل نهایی قفل است</h3>
                                <p style="font-size: 11px; color: #64748b; max-width: 380px; line-height: 1.6; margin: 0;">
                                    دسترسی به اطلاعات نهایی پروژه، آموزش‌های استفاده از سایت و مشخصات حساس سرور، منوط به تسویه حساب کامل مالی و تایید پرداخت‌ها توسط بخش حسابداری است.
                                </p>
                                <button wire:click="setActiveTab('finance')" class="hasht-manage-btn" style="padding: 8px 16px;">
                                    <span>مشاهده وضعیت تراکنش‌ها و امور مالی</span>
                                </button>
                            </div>
                        @else
                            @if(!$project->handover)
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 36px; text-align: center; gap: 12px;">
                                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #eff6ff; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">در حال آماده‌سازی بسته تحویل</h3>
                                    <p style="font-size: 11px; color: #64748b; max-width: 380px; line-height: 1.6; margin: 0;">
                                        تسویه مالی با موفقیت تایید شده است. تیم فنی در حال بارگذاری اطلاعات نهایی دسترسی‌ها و مستندات آموزشی پروژه شما است.
                                    </p>
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    <div style="padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
                                        <h3 style="font-size: 16px; font-weight: 800; color: #166534; margin: 0;">
                                            🎉 تبریک! پروژه شما با موفقیت تحویل شد
                                        </h3>
                                        <div style="font-size: 11px; color: #166534; line-height: 1.7; margin-top: 8px;">
                                            {!! $project->handover->congratulations_message !!}
                                        </div>
                                    </div>

                                    @if(is_array($project->handover->training_videos) && count($project->handover->training_videos) > 0)
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">ویدیوهای آموزشی استفاده از سایت</h4>
                                            <div class="hasht-grid-2">
                                                @foreach($project->handover->training_videos as $video)
                                                    <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                                        <span style="font-size: 11px; font-weight: 700; color: #0f172a;">{{ $video['title'] }}</span>
                                                        <a href="{{ $video['url'] }}" target="_blank" class="hasht-manage-btn" style="font-size: 10px; padding: 4px 10px;">
                                                            <span>مشاهده ویدیو</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>

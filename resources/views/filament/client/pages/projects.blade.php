<x-filament-panels::page>
    <style>
        .hasht-client-container { display: flex; flex-direction: column; gap: 20px; font-family: 'PeydaWebVF', sans-serif !important; direction: rtl; }
        .hasht-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        .hasht-sec-title { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; }

        .hasht-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }
        .hasht-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .hasht-proj-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }
        .hasht-proj-card:hover { transform: translateY(-2px); border-color: #cbd5e1; box-shadow: 0 6px 14px rgba(0,0,0,0.05); }

        .hasht-banner { position: relative; overflow: hidden; border-radius: 12px; padding: 18px 22px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .hasht-banner-amber { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .hasht-banner-indigo { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
        .hasht-banner-blue { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
        .hasht-banner-purple { background: #faf5ff; border-color: #e9d5ff; color: #6b21a8; }
        .hasht-banner-emerald { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .hasht-banner-sky { background: #f0f9ff; border-color: #bae6fd; color: #075985; }
        .hasht-banner-gray { background: #f8fafc; border-color: #e2e8f0; color: #334155; }

        .hasht-grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
        .hasht-grid-7 { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
        @media (max-width: 768px) {
            .hasht-grid-7 span { display: none; }
        }

        .hasht-manage-btn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 7px 16px; border-radius: 8px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s; font-size: 13px; cursor: pointer; }
        .hasht-manage-btn:hover { background: #e0e7ff; border-color: #a5b4fc; box-shadow: 0 2px 6px rgba(67,56,202,0.12); }
        .hasht-manage-btn:hover svg { transform: translateX(-3px); }
        .hasht-manage-btn svg { transition: transform 0.2s ease; }

        .hasht-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #f1f5f9; color: #334155; white-space: nowrap; }
        .hasht-badge-amber { background: #fef3c7; color: #92400e; }
        .hasht-badge-green { background: #dcfce7; color: #15803d; }

        .hasht-progress-track { width: 100%; background: #e2e8f0; border-radius: 9999px; height: 8px; overflow: hidden; }
        .hasht-progress-fill { background: #10b981; height: 100%; border-radius: 9999px; transition: width 0.5s ease; }

        .hasht-tab-bar { display: flex; border-bottom: 1px solid #e2e8f0; gap: 4px; overflow-x: auto; margin-top: 6px; }
        .hasht-tab-btn { padding: 10px 18px; font-size: 13px; font-weight: 700; border: none; background: transparent; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; display: inline-flex; align-items: center; gap: 6px; color: #64748b; transition: all 0.2s; white-space: nowrap; }
        .hasht-tab-btn-active { color: #4f46e5; border-bottom-color: #4f46e5; font-weight: 800; }
        .hasht-tab-btn:hover:not(.hasht-tab-btn-active) { color: #0f172a; }

        .custom-input { width: 100%; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; transition: border-color 0.2s; }
        .custom-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }

        svg { flex-shrink: 0; }
    </style>

    <div class="hasht-client-container">
        @if(!$selectedProjectId)
            <!-- STATE A: Projects Overview Grid -->
            <div class="hasht-card">
                <div class="hasht-sec-heading">
                    <div class="hasht-sec-title">
                        <svg style="width: 20px; height: 20px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
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
                                        <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0;">
                                            {{ $p->title }}
                                        </h3>
                                        <span class="hasht-badge">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
                                        تاریخ ثبت: {{ \App\Helpers\JalaliHelper::toJalali($p->created_at, 'Y/m/d') }}
                                    </div>
                                </div>

                                <!-- نوار پیشرفت دو رنگ -->
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700;">
                                        <span style="color: #10b981; display: flex; align-items: center; gap: 4px;">
                                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                                            پیشرفت: {{ \App\Helpers\JalaliHelper::toPersianDigits($statusInfo['percent']) }}٪
                                        </span>
                                        <span style="color: #64748b; display: flex; align-items: center; gap: 4px;">
                                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span>
                                            کار باقیمانده: {{ \App\Helpers\JalaliHelper::toPersianDigits($remainingPercent) }}٪
                                        </span>
                                    </div>
                                    <div class="hasht-progress-track">
                                        <div class="hasht-progress-fill" style="width: {{ $statusInfo['percent'] }}%"></div>
                                    </div>
                                </div>

                                <!-- بخش اکشن پایین کارت -->
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; padding-top: 6px; border-top: 1px solid #f1f5f9;">
                                    <button wire:click="selectProject({{ $p->id }})" class="hasht-manage-btn">
                                        <span>مدیریت پروژه</span>
                                        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
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
                    <div style="text-align: center; padding: 36px; color: #64748b; font-size: 13px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                        هیچ پروژه‌ای برای حساب کاربری شما یافت نشد.
                    </div>
                @endif
            </div>

        @else
            <!-- STATE B: Selected Project Workspace -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                <button wire:click="backToProjects" class="hasht-manage-btn" style="background: #f1f5f9; border-color: #cbd5e1; color: #334155;">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>بازگشت به لیست پروژه‌ها</span>
                </button>
                <div style="font-size: 13px; color: #64748b;">پروژه فعال: <strong style="color: #0f172a; font-size: 14px;">{{ $project->title }}</strong></div>
            </div>

            <!-- HERO NEXT ACTION BANNER -->
            @php $nextAction = $this->nextAction; @endphp
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
                            @if($nextAction['actionType'] === 'url')
                                <a href="{{ $nextAction['url'] }}" class="hasht-manage-btn">
                                    <span>{{ $nextAction['buttonText'] }}</span>
                                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </a>
                            @elseif($nextAction['actionType'] === 'tab')
                                <button wire:click="setActiveTab('{{ $nextAction['tab'] }}')" class="hasht-manage-btn">
                                    <span>{{ $nextAction['buttonText'] }}</span>
                                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- PASTEL PROGRESS HEADER & 7-PHASE STEPPER -->
            <div class="hasht-card" style="display: flex; flex-direction: column; gap: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="padding: 3px 8px; font-size: 11px; font-weight: 700; background: #eef2ff; color: #4338ca; border-radius: 6px; border: 1px solid #c7d2fe;">میزکار پروژه</span>
                            <span style="font-size: 12px; color: #64748b;">شناسه: #{{ $project->id }}</span>
                        </div>
                        <h2 style="font-size: 20px; font-weight: 900; color: #0f172a; margin-top: 6px;">{{ $project->title }}</h2>
                    </div>
                    
                    <div>
                        <span class="hasht-badge" style="font-size: 13px; padding: 6px 14px; background: #f1f5f9; border: 1px solid #cbd5e1;">
                            وضعیت: {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Progress Bar & Stepper -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @php $remaining = 100 - $progressPercent; @endphp
                    <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700;">
                        <span style="color: #10b981; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            پیشرفت: {{ \App\Helpers\JalaliHelper::toPersianDigits($progressPercent) }}٪
                        </span>
                        <span style="color: #64748b; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span>
                            کار باقیمانده: {{ \App\Helpers\JalaliHelper::toPersianDigits($remaining) }}٪
                        </span>
                    </div>
                    
                    <div class="hasht-progress-track">
                        <div class="hasht-progress-fill" style="width: {{ $progressPercent }}%"></div>
                    </div>

                    <!-- 7-Phase Interactive Micro Dots -->
                    <div class="hasht-grid-7" style="margin-top: 6px;">
                        @foreach($statuses as $key => $info)
                            @php
                                $isCurrent = $project->status === $key;
                                $isPassed = $progressPercent >= $info['percent'];
                            @endphp
                            <div wire:click="setActiveTab('roadmap')" style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 6px;" title="{{ $info['label'] }}">
                                <div style="width: 100%; height: 6px; border-radius: 9999px; transition: all 0.3s; {{ $isCurrent ? 'background: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe;' : ($isPassed ? 'background: #10b981;' : 'background: #cbd5e1;') }}"></div>
                                <span style="font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; {{ $isCurrent ? 'font-weight: 900; color: #4f46e5;' : ($isPassed ? 'color: #334155; font-weight: 700;' : 'color: #94a3b8;') }}">
                                    {{ $info['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Workspace Navigation Tabs (Separated Questionnaire and Credentials) -->
            <div class="hasht-tab-bar">
                <button wire:click="setActiveTab('roadmap')" class="hasht-tab-btn {{ $activeTab === 'roadmap' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 012.447 15.5V5.5a2 2 0 011.106-1.789L9 1m0 19v-9m0 9l5.447-2.724A2 2 0 0019.553 15.5V5.5a2 2 0 00-1.106-1.789L15 1m-6 9V1m0 9l6-3.333"/></svg>
                    <span>نقشه راه فازها</span>
                </button>
                <button wire:click="setActiveTab('finance')" class="hasht-tab-btn {{ $activeTab === 'finance' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>قرارداد و امور مالی</span>
                </button>
                <button wire:click="setActiveTab('questionnaire')" class="hasht-tab-btn {{ ($activeTab === 'questionnaire' || $activeTab === 'brief') ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>پرسشنامه نیازمندی‌ها</span>
                </button>
                <button wire:click="setActiveTab('credentials')" class="hasht-tab-btn {{ $activeTab === 'credentials' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>گاوصندوق دسترسی‌ها</span>
                </button>
                <button wire:click="setActiveTab('demo')" class="hasht-tab-btn {{ $activeTab === 'demo' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>بازنگری دمو</span>
                </button>
                <button wire:click="setActiveTab('handover')" class="hasht-tab-btn {{ $activeTab === 'handover' ? 'hasht-tab-btn-active' : '' }}">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0h-4m0 0v13m0 0h12"/></svg>
                    <span>بسته تحویل نهایی</span>
                </button>
            </div>

            <!-- Tab Content -->
            <div style="margin-top: 6px;">
                @if($activeTab === 'roadmap')
                    <!-- ROADMAP TAB -->
                    <div class="hasht-card">
                        <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 24px;">مراحل اجرای پروژه</h3>
                        
                        <div style="display: flex; flex-direction: column;">
                            @foreach($statuses as $key => $info)
                                @php
                                    $isCurrent = $project->status === $key;
                                    $isPassed = $progressPercent >= $info['percent'];
                                @endphp
                                <div style="display: flex; gap: 16px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; {{ $isCurrent ? 'background: #4f46e5; border-color: #4f46e5; color: #ffffff; box-shadow: 0 0 0 4px #eef2ff;' : ($isPassed ? 'background: #10b981; border-color: #10b981; color: #ffffff;' : 'background: #ffffff; border-color: #cbd5e1; color: #94a3b8;') }}">
                                            @if($isPassed && !$isCurrent)
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <span>{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        @if(!$loop->last)
                                            <div style="width: 2px; flex-grow: 1; background: #e2e8f0; margin: 4px 0; min-height: 28px;"></div>
                                        @endif
                                    </div>

                                    <div style="padding-bottom: 24px; padding-top: 4px; flex-grow: 1;">
                                        <h4 style="font-size: 14px; font-weight: 800; margin: 0; {{ $isCurrent ? 'color: #4f46e5;' : ($isPassed ? 'color: #0f172a;' : 'color: #94a3b8;') }}">
                                            {{ $info['label'] }}
                                        </h4>
                                        @if($isCurrent)
                                            <p style="font-size: 13px; color: #64748b; margin-top: 6px; line-height: 1.6;">
                                                پروژه شما هم‌اکنون در این مرحله قرار دارد. اقدامات مربوط به امور مالی، پرسشنامه و دمو را می‌توانید از تب‌های بالای صفحه پیگیری کنید.
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
                        <!-- Proforma Card -->
                        @if($project->proforma)
                            <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                                <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin: 0;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <svg style="width: 18px; height: 18px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>پیش‌فاکتور خدمات</span>
                                    </div>
                                    @if($project->proforma->is_approved_by_client)
                                        <span class="hasht-badge hasht-badge-green">تایید شده</span>
                                    @else
                                        <span class="hasht-badge hasht-badge-amber">در انتظار تایید</span>
                                    @endif
                                </h3>

                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    @if($project->proforma->items && count($project->proforma->items) > 0)
                                        @foreach($project->proforma->items as $item)
                                            <div style="display: flex; justify-content: space-between; font-size: 13px; padding: 8px 12px; background: #f8fafc; border-radius: 6px;">
                                                <span style="color: #334155; font-weight: 700;">{{ $item['description'] ?? 'آیتم' }} ({{ $item['quantity'] ?? 1 }} عدد)</span>
                                                <span style="color: #0f172a; font-weight: 800;">{{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1)) }} تومان</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div style="margin-top: auto; padding-top: 12px; border-top: 1px dashed #cbd5e1; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 13px;">
                                        <span style="color: #64748b;">مبلغ کل:</span>
                                        <span style="color: #334155; font-weight: 700;">{{ number_format($project->proforma->total_amount) }} تومان</span>
                                    </div>
                                    @if($project->proforma->discount > 0)
                                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                                            <span style="color: #ef4444;">تخفیف اعمال شده:</span>
                                            <span style="color: #ef4444; font-weight: 700;">{{ number_format($project->proforma->discount) }} تومان</span>
                                        </div>
                                    @endif
                                    <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 900;">
                                        <span style="color: #0f172a;">مبلغ نهایی پرداخت:</span>
                                        <span style="color: #4f46e5;">{{ number_format($project->proforma->final_amount) }} تومان</span>
                                    </div>
                                </div>

                                @if(!$project->proforma->is_approved_by_client)
                                    <div style="margin-top: 12px;">
                                        <button wire:click="approveProforma" class="hasht-manage-btn" style="width: 100%; justify-content: center; padding: 10px; font-size: 14px; background: #10b981; color: white; border-color: #059669;">
                                            <span>تایید پیش‌فاکتور و شروع مرحله قرارداد</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Contract Card -->
                        <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin: 0;">
                                <svg style="width: 18px; height: 18px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>قرارداد همکاری پروژه</span>
                            </h3>

                            @if($project->contract)
                                @if($project->contract->signed_at)
                                    <div style="padding: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; color: #166534; font-size: 13px; display: flex; flex-direction: column; gap: 6px;">
                                        <div style="display: flex; align-items: center; gap: 6px; font-weight: 800; font-size: 14px;">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>قرارداد با موفقیت امضای دیجیتال شده است</span>
                                        </div>
                                        <p style="margin: 0;">امضاکننده: {{ $project->contract->signature_name }} | کد ملی: {{ $project->contract->signature_national_code }}</p>
                                        <p style="margin: 0;">تاریخ امضا: {{ \App\Helpers\JalaliHelper::toJalali($project->contract->signed_at, 'Y/m/d H:i') }}</p>
                                    </div>
                                    <div style="padding: 16px; background: #f8fafc; border-radius: 10px; max-height: 320px; overflow-y: auto; font-size: 13px; color: #334155; line-height: 1.8; border: 1px solid #e2e8f0;">
                                        {!! $renderedContractContent !!}
                                    </div>
                                @else
                                    <div style="padding: 16px; background: #f8fafc; border-radius: 10px; max-height: 320px; overflow-y: auto; font-size: 13px; color: #334155; line-height: 1.8; border: 1px solid #e2e8f0;">
                                        {!! $renderedContractContent !!}
                                    </div>
                                    
                                    <form wire:submit.prevent="signContract" style="display: flex; flex-direction: column; gap: 14px; padding-top: 14px; border-top: 1px solid #e2e8f0;">
                                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">امضای دیجیتال قرارداد</h4>
                                        <div class="hasht-grid-2">
                                            <div>
                                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 4px;">نام و نام خانوادگی امضاکننده</label>
                                                <input type="text" wire:model="sigName" placeholder="مثال: علی رضایی" class="custom-input" required>
                                                @error('sigName') <span style="font-size: 11px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 4px;">کد ملی ده رقمی</label>
                                                <input type="text" wire:model="sigNationalCode" maxlength="10" placeholder="مثال: 0012345678" class="custom-input" required>
                                                @error('sigNationalCode') <span style="font-size: 11px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="hasht-manage-btn" style="width: 100%; justify-content: center; padding: 10px; font-size: 14px;">
                                            <span>امضا و پذیرش تعهدات قرارداد</span>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p style="font-size: 13px; color: #64748b; text-align: center; padding: 24px;">قرارداد همکاری پروژه به زودی توسط مدیریت بارگذاری خواهد شد.</p>
                            @endif
                        </div>

                        <!-- Finance Card -->
                        <div class="hasht-card" style="display: flex; flex-direction: column; gap: 16px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin: 0;">
                                <svg style="width: 18px; height: 18px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>حسابداری و ثبت فیش‌های بانکی</span>
                            </h3>

                            <div style="padding: 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; font-size: 13px; color: #1e40af; display: flex; flex-direction: column; gap: 4px;">
                                <p style="font-weight: 800; margin: 0;">اطلاعات حساب جهت واریز پیش‌پرداخت / فاکتورها:</p>
                                <p style="margin: 0;">شماره کارت: ۵۰۲۲-۲۹۱۰-۱۲۳۴-۵۶۷۸</p>
                                <p style="margin: 0;">به نام: مدیریت شرکت هشت</p>
                                <p style="opacity: 0.85; font-size: 11px; margin-top: 4px;">پس از واریز مبلغ، تصویر فیش واریزی را جهت تایید و فعال‌سازی مراحل پروژه در فرم زیر ثبت کنید.</p>
                            </div>

                            @php
                                $hasPendingPayment = $project->payments->where('status', 'pending')->count() > 0;
                            @endphp

                            @if($project->status === 'contract')
                                @if($hasPendingPayment)
                                    <div style="padding: 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; color: #92400e; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>فیش واریزی شما با موفقیت دریافت شد و هم‌اکنون در انتظار بررسی و تایید واحد حسابداری است. نیازی به ارسال مجدد نیست.</span>
                                    </div>
                                @else
                                    <form wire:submit.prevent="uploadSlip" style="display: flex; flex-direction: column; gap: 14px; padding-top: 6px;">
                                        <h4 style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 0;">ثبت فیش واریز جدید</h4>
                                        <div class="hasht-grid-2">
                                            <div>
                                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 4px;">مبلغ واریزی (تومان)</label>
                                                <input type="number" wire:model="paymentAmount" placeholder="مثال: 5000000" class="custom-input" required>
                                                @error('paymentAmount') <span style="font-size: 11px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 4px;">تصویر فیش بانکی</label>
                                                <input type="file" wire:model="bankSlipFile" style="font-size: 12px; color: #64748b;" required>
                                                @error('bankSlipFile') <span style="font-size: 11px; color: #ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="hasht-manage-btn" style="width: 100%; justify-content: center; padding: 10px; font-size: 14px;">
                                            <span>ارسال و ثبت فیش پرداخت</span>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <div style="display: flex; flex-direction: column; gap: 10px; padding-top: 6px;">
                                <h4 style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 0;">سوابق تراکنش‌ها</h4>
                                @if($project->payments->count() > 0)
                                    <div style="overflow-x: auto;">
                                        <table style="width: 100%; font-size: 12px; text-align: right; border-collapse: collapse;">
                                            <thead>
                                                <tr style="background: #f8fafc; color: #475569; border-bottom: 1px solid #e2e8f0;">
                                                    <th style="padding: 8px 12px;">مبلغ</th>
                                                    <th style="padding: 8px 12px;">وضعیت بررسی</th>
                                                    <th style="padding: 8px 12px;">تاریخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($project->payments as $payment)
                                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                                        <td style="padding: 8px 12px; font-weight: 700; color: #0f172a;">
                                                            {{ number_format($payment->amount) }} تومان
                                                        </td>
                                                        <td style="padding: 8px 12px;">
                                                            @if($payment->status === 'approved')
                                                                <span style="padding: 3px 8px; font-size: 11px; font-weight: 700; background: #f0fdf4; color: #166534; border-radius: 6px;">تایید شده</span>
                                                            @elseif($payment->status === 'rejected')
                                                                <span style="padding: 3px 8px; font-size: 11px; font-weight: 700; background: #fef2f2; color: #991b1b; border-radius: 6px;">رد شده</span>
                                                            @else
                                                                <span style="padding: 3px 8px; font-size: 11px; font-weight: 700; background: #fffbeb; color: #92400e; border-radius: 6px;">در انتظار بررسی</span>
                                                            @endif
                                                        </td>
                                                        <td style="padding: 8px 12px; font-size: 11px; color: #94a3b8;">
                                                            {{ \App\Helpers\JalaliHelper::toJalali($payment->created_at, 'Y/m/d') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p style="font-size: 12px; color: #64748b; text-align: center; padding: 16px;">تراکنشی یافت نشد.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'questionnaire' || $activeTab === 'brief')
                    <!-- TAB 3: QUESTIONNAIRE ANSWERS (پرسشنامه نیازمندی‌ها) -->
                    <div class="hasht-card" style="display: flex; flex-direction: column; gap: 18px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
                                <svg style="width: 18px; height: 18px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>پاسخ‌های ثبت‌شده در پرسشنامه نیازمندی‌ها</span>
                            </h3>
                            @if($project->briefAnswer)
                                <a href="{{ \App\Filament\Client\Pages\CompleteBrief::getUrl() }}" class="hasht-manage-btn" style="font-size: 12px; padding: 6px 14px;">
                                    <span>ویرایش / تکمیل مجدد پرسشنامه</span>
                                </a>
                            @endif
                        </div>

                        @if($project->status === 'brief' && !$project->briefAnswer)
                            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 24px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0;">پرسشنامه نیازمندی‌ها هنوز تکمیل نشده است</h4>
                                <p style="font-size: 13px; color: #475569; max-width: 420px; line-height: 1.6; margin: 0;">برای شروع فرآیند کدنویسی و تحلیل فنی، لطفاً اطلاعات اولیه برند و نیازمندی‌های سیستم را تکمیل فرمایید.</p>
                                <a href="{{ \App\Filament\Client\Pages\CompleteBrief::getUrl() }}" class="hasht-manage-btn" style="padding: 10px 20px; font-size: 14px;">
                                    <span>ورود به فرم پرسشنامه</span>
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </a>
                            </div>
                        @else
                            @if($project->briefAnswer && is_array($project->briefAnswer->dynamic_answers))
                                @php
                                    $keyMap = [
                                        'brand_name' => 'نام برند / شرکت',
                                        'brand_name_fa' => 'نام برند (فارسی)',
                                        'brand_name_en' => 'نام برند (انگلیسی)',
                                        'target_audience' => 'مخاطبان هدف',
                                        'color_palette' => 'پالت رنگی مورد علاقه',
                                        'domain_name' => 'دامنه اصلی سایت',
                                        'business_summary' => 'خلاصه فعالیت کسب‌وکار',
                                        'required_features' => 'امکانات مورد نیاز',
                                        'reference_websites' => 'نمونه سایت‌های مورد علاقه‌مندی',
                                    ];
                                @endphp
                                <div class="hasht-grid-2">
                                    @foreach($project->briefAnswer->dynamic_answers as $key => $value)
                                        @if(!empty($value))
                                            @php
                                                $displayKey = $keyMap[$key] ?? (__($key) != $key ? __($key) : str_replace('_', ' ', ucfirst($key)));
                                            @endphp
                                            <div style="padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; display: flex; flex-direction: column; gap: 6px;">
                                                <span style="font-size: 12px; font-weight: 700; color: #64748b; display: flex; align-items: center; gap: 4px;">
                                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #4f46e5; display: inline-block;"></span>
                                                    {{ $displayKey }}
                                                </span>
                                                <div style="font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.6;">
                                                    @if(is_array($value))
                                                        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                                                            @foreach($value as $item)
                                                                <span class="hasht-badge" style="background: #eef2ff; color: #4338ca; border-color: #c7d2fe;">
                                                                    {{ is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p style="font-size: 13px; color: #64748b; text-align: center; padding: 24px;">پرسشنامه نیازمندی‌ها برای این پروژه هنوز ثبت نشده است.</p>
                            @endif
                        @endif
                    </div>

                @elseif($activeTab === 'credentials')
                    <!-- TAB 4: VAULT CREDENTIALS (گاوصندوق دسترسی‌ها) -->
                    <div class="hasht-card" style="display: flex; flex-direction: column; gap: 18px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
                                <svg style="width: 18px; height: 18px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>گاوصندوق دسترسی‌های هاست و دامنه (رمزنگاری‌شده)</span>
                            </h3>
                            <span style="padding: 4px 10px; font-size: 12px; font-weight: 700; background: #f0fdf4; color: #166534; border-radius: 6px;">محفوظ و ایمن</span>
                        </div>

                        @if($project->credential)
                            <div class="hasht-grid-2" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                                <div style="padding: 14px; background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <span style="font-size: 12px; color: #64748b; display: block;">ارائه‌دهنده هاست / سرور:</span>
                                    <span style="font-weight: 800; color: #0f172a; margin-top: 4px; display: block; font-size: 14px;">{{ $project->credential->host_provider ?: 'ثبت نشده' }}</span>
                                </div>

                                <div x-data="{ copied: false }" style="padding: 14px; background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                                    <div>
                                        <span style="font-size: 12px; color: #64748b; display: block;">آدرس پنل هاست:</span>
                                        <span style="font-weight: 800; color: #0f172a; margin-top: 4px; display: block; direction: ltr; text-align: right; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px;">{{ $project->credential->host_panel_url ?: 'ثبت نشده' }}</span>
                                    </div>
                                    @if($project->credential->host_panel_url)
                                        <button type="button" x-on:click="navigator.clipboard.writeText('{{ $project->credential->host_panel_url }}'); copied = true; setTimeout(() => copied = false, 2000)" style="padding: 6px 10px; border-radius: 6px; background: #f1f5f9; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.2s;">
                                            <template x-if="!copied">
                                                <svg style="width: 16px; height: 16px; color: #475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </template>
                                            <template x-if="copied">
                                                <svg style="width: 16px; height: 16px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </button>
                                    @endif
                                </div>

                                <div style="padding: 14px; background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <span style="font-size: 12px; color: #64748b; display: block;">ثبت‌کننده دامنه:</span>
                                    <span style="font-weight: 800; color: #0f172a; margin-top: 4px; display: block; font-size: 14px;">{{ $project->credential->domain_provider ?: 'ثبت نشده' }}</span>
                                </div>
                            </div>

                            <p style="font-size: 12px; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 6px; margin: 0; background: #fffbeb; padding: 12px; border-radius: 8px; border: 1px solid #fde68a;">
                                <svg style="width: 16px; height: 16px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>نکته امنیتی: کلمات عبور به صورت رمزنگاری دوطرفه ذخیره شده و صرفاً جهت اجرای پروژه توسط تیم توسعه قابل بهره‌برداری هستند.</span>
                            </p>
                        @else
                            <div style="text-align: center; padding: 32px; color: #64748b; font-size: 13px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                                دسترسی‌های هاست و دامنه هنوز در گاوصندوق امن ثبت نشده‌اند.
                            </div>
                        @endif
                    </div>

                @elseif($activeTab === 'demo')
                    <!-- DEMO TAB -->
                    <div class="hasht-card" style="display: flex; flex-direction: column; gap: 18px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                            <div>
                                <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
                                    <svg style="width: 18px; height: 18px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>بررسی دمو و پیش‌نمایش پروژه</span>
                                </h3>
                                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">نسخه آزمایشی پروژه خود را بررسی کنید و نظرات خود را برای اصلاح یا تایید نهایی بنویسید.</p>
                            </div>
                        </div>

                        @if(!in_array($project->status, ['ui_design', 'review']) && $project->feedbacks->count() === 0)
                            <div style="padding: 28px; background: #f8fafc; border-radius: 10px; text-align: center; border: 1px dashed #cbd5e1;">
                                <p style="font-size: 13px; color: #64748b; margin: 0;">پروژه در حال حاضر در فاز طراحی یا دمو نیست. به محض اتمام طراحی و انتشار طرح/دمو توسط تیم فنی، این بخش فعال خواهد شد.</p>
                            </div>
                        @else
                            @if(!$project->demo_url)
                                <div style="padding: 24px; background: #f8fafc; border-radius: 10px; text-align: center; border: 1px dashed #cbd5e1;">
                                    <p style="font-size: 13px; color: #64748b; margin: 0;">لینک طرح یا دموی پروژه در حال آماده‌سازی است. به محض قرار گرفتن لینک، از این بخش می‌توانید آن را بررسی کنید.</p>
                                </div>
                            @else
                                <!-- Demo Iframe / Button -->
                                <div style="display: flex; flex-direction: column; gap: 12px; padding: 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    @if(str_contains($project->demo_url, 'figma.com'))
                                        <iframe style="border: 1px solid rgba(0, 0, 0, 0.1);" width="100%" height="450" src="{{ str_contains($project->demo_url, 'embed') ? $project->demo_url : 'https://www.figma.com/embed?embed_host=share&url=' . urlencode($project->demo_url) }}" allowfullscreen></iframe>
                                    @endif
                                    <a href="{{ $project->demo_url }}" target="_blank" class="hasht-manage-btn" style="padding: 10px 22px; font-size: 14px; justify-content: center;">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>مشاهده لینک در تب جدید (تمام‌صفحه)</span>
                                    </a>
                                </div>

                                @if(in_array($project->status, ['ui_design', 'review']))
                                    <!-- Feedback Form -->
                                    <div style="display: flex; flex-direction: column; gap: 12px;">
                                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">ثبت نظر یا اعلام مغایرت‌ها:</h4>
                                        <textarea wire:model="feedbackNotes" placeholder="در صورتی که بخش‌های نیاز به اصلاح وجود دارد، جزئیات آن را در این قسمت به صورت گام به گام وارد نمایید..." rows="3" class="custom-input"></textarea>
                                        @error('feedbackNotes') <span style="font-size: 11px; color: #ef4444;">{{ $message }}</span> @enderror

                                        <div class="hasht-grid-2">
                                            <button wire:click="submitFeedback('needs_changes')" class="hasht-manage-btn" style="background: #fef2f2; border-color: #fca5a5; color: #991b1b; justify-content: center; padding: 10px; font-size: 13px;">
                                                <span>ثبت نیاز به اصلاحات و تغییرات</span>
                                            </button>
                                            <button wire:click="submitFeedback('approved')" class="hasht-manage-btn" style="background: #16a34a; border-color: #15803d; color: #ffffff !important; justify-content: center; padding: 10px; font-size: 13px;">
                                                <span>تایید نهایی و ارسال به مرحله بعد</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- History of Feedbacks -->
                                <div style="display: flex; flex-direction: column; gap: 10px; padding-top: 14px; border-top: 1px solid #e2e8f0;">
                                    <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">تاریخچه بازخوردها</h4>
                                    @forelse($project->feedbacks as $fb)
                                        <div style="padding: 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
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
                                                <span style="font-size: 11px; color: #94a3b8;">{{ \App\Helpers\JalaliHelper::toJalali($fb->created_at, 'Y/m/d H:i') }}</span>
                                            </div>
                                            <p style="color: #334155; line-height: 1.6; margin: 0;">{{ $fb->notes }}</p>
                                        </div>
                                    @empty
                                        <p style="font-size: 12px; color: #64748b; text-align: center; padding: 8px;">هیچ فیدبکی تاکنون ثبت نشده است.</p>
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
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center; gap: 14px;">
                                <div style="width: 56px; height: 56px; border-radius: 50%; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                    <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">بسته تحویل نهایی قفل است</h3>
                                <p style="font-size: 13px; color: #64748b; max-width: 400px; line-height: 1.7; margin: 0;">
                                    دسترسی به اطلاعات نهایی پروژه، آموزش‌های استفاده از سایت و مشخصات حساس سرور، منوط به تسویه حساب کامل مالی و تایید پرداخت‌ها توسط بخش حسابداری است.
                                </p>
                                <button wire:click="setActiveTab('finance')" class="hasht-manage-btn" style="padding: 10px 20px; font-size: 13px;">
                                    <span>مشاهده وضعیت تراکنش‌ها و امور مالی</span>
                                </button>
                            </div>
                        @else
                            @if(!$project->handover)
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center; gap: 14px;">
                                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #eff6ff; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                        <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">در حال آماده‌سازی بسته تحویل</h3>
                                    <p style="font-size: 13px; color: #64748b; max-width: 400px; line-height: 1.7; margin: 0;">
                                        تسویه مالی با موفقیت تایید شده است. تیم فنی در حال بارگذاری اطلاعات نهایی دسترسی‌ها و مستندات آموزشی پروژه شما است.
                                    </p>
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    <div style="padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
                                        <h3 style="font-size: 18px; font-weight: 900; color: #166534; margin: 0;">
                                            🎉 تبریک! پروژه شما با موفقیت تحویل شد
                                        </h3>
                                        <div style="font-size: 13px; color: #166534; line-height: 1.8; margin-top: 10px;">
                                            {!! $project->handover->congratulations_message !!}
                                        </div>
                                    </div>

                                    @if(is_array($project->handover->training_videos) && count($project->handover->training_videos) > 0)
                                        <div style="display: flex; flex-direction: column; gap: 12px;">
                                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">ویدیوهای آموزشی استفاده از سایت</h4>
                                            <div class="hasht-grid-2">
                                                @foreach($project->handover->training_videos as $video)
                                                    <div style="padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                                        <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $video['title'] }}</span>
                                                        <a href="{{ $video['url'] }}" target="_blank" class="hasht-manage-btn" style="font-size: 12px; padding: 6px 12px;">
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

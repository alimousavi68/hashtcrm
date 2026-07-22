@php
    $record = $getRecord();
    if (!$record) return;

    $percent = $record->getProgressPercent();
    $remainingPercent = 100 - $percent;
    $statusLabel = $record->getStatusLabel();
    $clientName = $record->client?->name ?? 'نامشخص';
    $clientPhone = $record->client?->phone ?? '';

    // Status colors
    $badgeBg = match($record->status) {
        'draft' => '#f1f5f9',
        'brief' => '#fef3c7',
        'contract' => '#e0f2fe',
        'in_progress' => '#eef2ff',
        'review' => '#ffe4e6',
        'ready_handover' => '#ccfbf1',
        'completed' => '#dcfce7',
        default => '#f1f5f9',
    };

    $badgeText = match($record->status) {
        'draft' => '#334155',
        'brief' => '#92400e',
        'contract' => '#0369a1',
        'in_progress' => '#4338ca',
        'review' => '#be123c',
        'ready_handover' => '#0f766e',
        'completed' => '#166534',
        default => '#334155',
    };

    // Calculate deadline
    $daysRemaining = null;
    $isOverdue = false;
    if ($record->feedback_deadline) {
        $now = \Carbon\Carbon::now();
        $deadline = \Carbon\Carbon::parse($record->feedback_deadline);
        $diff = (int) $now->diffInDays($deadline, false);
        if ($diff < 0) {
            $isOverdue = true;
            $daysRemaining = \App\Helpers\JalaliHelper::toPersianDigits((string) abs($diff));
        } else {
            $daysRemaining = \App\Helpers\JalaliHelper::toPersianDigits((string) $diff);
        }
    }
@endphp

<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); width: 100%; transition: all 0.2s;">

    <!-- 1. هدر کارت پروژه -->
    <div>
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
            <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.4;">
                {{ $record->title }}
            </h3>
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                <span style="padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: {{ $badgeBg }}; color: {{ $badgeText }}; white-space: nowrap;">
                    {{ $statusLabel }}
                </span>
                @if($record->is_settled)
                    <span style="padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 4px; background: #dcfce7; color: #166534; white-space: nowrap;">
                        ✓ تسویه‌شده
                    </span>
                @else
                    <span style="padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 4px; background: #fffbeb; color: #b45309; white-space: nowrap;">
                        ⏳ تسویه‌نشده
                    </span>
                @endif
            </div>
        </div>

        <!-- کارفرما -->
        <div style="display: flex; align-items: center; gap: 6px; margin-top: 8px; font-size: 11px; color: #475569; font-weight: 500;">
            <svg style="width: 14px; height: 14px; flex-shrink: 0; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span>کارفرما: <strong style="color: #0f172a;">{{ $clientName }}</strong></span>
            @if($clientPhone)
                <span style="color: #94a3b8;">| {{ $clientPhone }}</span>
            @endif
        </div>
    </div>

    <!-- 2. نوار پیشرفت و کار باقیمانده -->
    <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 6px;">
        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; font-weight: 500;">
            <span style="color: #047857; display: flex; align-items: center; gap: 4px;">
                <span style="width: 7px; height: 7px; border-radius: 9999px; background: #10b981;"></span>
                پیشرفت: ٪<strong style="font-weight: 700; color: #065f46;">{{ \App\Helpers\JalaliHelper::toPersianDigits((string) $percent) }}</strong>
            </span>
            <span style="color: #475569; display: flex; align-items: center; gap: 4px;">
                <span style="width: 7px; height: 7px; border-radius: 9999px; background: #64748b;"></span>
                کار باقیمانده: ٪<strong style="font-weight: 700; color: #334155;">{{ \App\Helpers\JalaliHelper::toPersianDigits((string) $remainingPercent) }}</strong>
            </span>
        </div>

        <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; display: flex;">
            <div style="width: {{ $percent }}%; height: 100%; background: #10b981; transition: width 0.4s ease; border-radius: 9999px;"></div>
        </div>
    </div>

    <!-- 3. دکمه‌های میانبر میان‌ماژولی پروژه (بریف، قرارداد، مالی، تیکت، تحویل) -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 4px; background: #f8fafc; padding: 6px; border-radius: 8px; border: 1px solid #f1f5f9; font-size: 10px;">
        <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $record->id]) }}" title="پرسشنامه و بریف" style="color: #475569; font-weight: 600; text-decoration: none; padding: 3px 6px; border-radius: 4px; background: #fff;">📋 بریف</a>
        <a href="{{ route('filament.admin.resources.projects.contract', ['record' => $record->id]) }}" title="قرارداد و امضا" style="color: #475569; font-weight: 600; text-decoration: none; padding: 3px 6px; border-radius: 4px; background: #fff;">📜 قرارداد</a>
        <a href="{{ route('filament.admin.resources.projects.payments', ['record' => $record->id]) }}" title="تراکنش‌ها و فیش‌ها" style="color: #475569; font-weight: 600; text-decoration: none; padding: 3px 6px; border-radius: 4px; background: #fff;">💳 مالی</a>
        <a href="{{ route('filament.admin.resources.projects.vault', ['record' => $record->id]) }}" title="گاوصندوق امن" style="color: #475569; font-weight: 600; text-decoration: none; padding: 3px 6px; border-radius: 4px; background: #fff;">🔒 Vault</a>
        <a href="{{ route('filament.admin.resources.projects.handover', ['record' => $record->id]) }}" title="بسته تحویل" style="color: #475569; font-weight: 600; text-decoration: none; padding: 3px 6px; border-radius: 4px; background: #fff;">📦 تحویل</a>
    </div>

    <!-- 4. فوتر کارت: وضعیت ددلاین و دکمه اصلی اقدام -->
    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 8px; border-top: 1px solid #f1f5f9; font-size: 11px;">
        <div>
            @if($daysRemaining !== null)
                @if($isOverdue)
                    <span style="display: inline-flex; align-items: center; gap: 4px; color: #be123c; font-weight: 700; background: #ffe4e6; padding: 3px 8px; border-radius: 6px;">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        {{ $daysRemaining }} روز تاخیر
                    </span>
                @else
                    <span style="display: inline-flex; align-items: center; gap: 4px; color: #b45309; font-weight: 700; background: #fef3c7; padding: 3px 8px; border-radius: 6px;">
                        <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        {{ $daysRemaining }} روز باقیمانده
                    </span>
                @endif
            @else
                <span style="color: #64748b; font-weight: 500; background: #f8fafc; padding: 3px 8px; border-radius: 6px;">ددلاین ثبت‌نشده</span>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            @if($record->demo_url)
                <a href="{{ $record->demo_url }}" target="_blank" title="مشاهده دمو آنلاین" style="display: inline-flex; align-items: center; gap: 3px; font-weight: 700; color: #0369a1; text-decoration: none; padding: 5px 8px; border-radius: 6px; background: #f0f9ff; border: 1px solid #bae6fd;">
                    🔗 دمو
                </a>
            @endif
            <a href="{{ route('filament.admin.resources.projects.brief', ['record' => $record->id]) }}" 
               aria-label="مدیریت کامل پروژه {{ $record->title }}"
               style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 6px 12px; border-radius: 6px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s;">
                <span>مدیریت پروژه</span>
                <svg style="width: 13px; height: 13px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>
    </div>

</div>

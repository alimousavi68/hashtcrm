@php
    $toPersian = fn($number) => \App\Helpers\JalaliHelper::toPersianDigits((string) ($number ?? 0));
@endphp

<div dir="rtl" style="width: 100%;">

    <!-- کادر فیلترها و جستجوی آنلاین -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            
            <!-- تب‌های فیلتر -->
            <div style="display: flex; align-items: center; gap: 4px; background: #f1f5f9; padding: 4px; border-radius: 8px; flex-wrap: wrap;">
                
                <button wire:click="setTab('all')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'all' ? 'background: #4f46e5; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(79,70,229,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span>همه الگوها</span>
                </button>

                <button wire:click="setTab('active')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'active' ? 'background: #16a34a; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(22,163,74,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>الگوهای فعال</span>
                </button>

                <button wire:click="setTab('wizard')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'wizard' ? 'background: #d97706; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(217,119,6,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    <span>حالت ویزاردی (گام‌به‌گام)</span>
                </button>

                <button wire:click="setTab('inactive')" 
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'inactive' ? 'background: #dc2626; color: #ffffff; font-weight: 700; box-shadow: 0 2px 4px rgba(220,38,38,0.25);' : 'background: transparent; color: #334155; font-weight: 600;' }}">
                    <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>غیرفعال‌ها</span>
                </button>
            </div>

            <!-- کادر جستجو -->
            <div style="position: relative; min-width: 240px;">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="جستجو در عنوان پرسشنامه..." 
                       style="width: 100%; padding: 6px 36px 6px 12px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; color: #0f172a; outline: none; transition: border-color 0.2s;" />
                <svg style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>

        </div>
    </div>

    <!-- شبکه‌بندی کارت‌های پرسشنامه‌ها -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">

        @forelse($templates as $record)
            <!-- کارت پرسشنامه -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); transition: all 0.2s;">
                
                <!-- هدر کارت -->
                <div>
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
                        <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.4;">
                            {{ $record->name }}
                        </h3>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                            @if($record->is_active)
                                <span style="padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: #dcfce7; color: #166534; white-space: nowrap; display: inline-flex; align-items: center; gap: 3px;">
                                    <svg style="width: 10px; height: 10px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    فعال
                                </span>
                            @else
                                <span style="padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; background: #ffe4e6; color: #991b1b; white-space: nowrap;">
                                    غیرفعال
                                </span>
                            @endif

                            @if($record->wizard_mode)
                                <span style="padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 4px; background: #fef3c7; color: #b45309; white-space: nowrap;">
                                    ⚡ ویزاردی (گام‌به‌گام)
                                </span>
                            @else
                                <span style="padding: 2px 6px; font-size: 9px; font-weight: 600; border-radius: 4px; background: #f1f5f9; color: #475569; white-space: nowrap;">
                                    تک‌صفحه‌ای
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($record->guide_notice)
                        <div style="font-size: 11px; color: #64748b; margin-top: 6px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $record->guide_notice }}
                        </div>
                    @endif
                </div>

                <!-- آمار و فیلدهای الگو -->
                <div style="background: #f8fafc; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; text-align: center;">
                    <div>
                        <div style="font-size: 10px; color: #64748b;">تعداد فیلدها</div>
                        <div style="font-size: 13px; font-weight: 800; color: #4338ca; margin-top: 2px;">
                            {{ $toPersian($record->questions_count) }}
                        </div>
                    </div>
                    <div style="border-right: 1px solid #e2e8f0; border-left: 1px solid #e2e8f0;">
                        <div style="font-size: 10px; color: #64748b;">پاسخ‌ها</div>
                        <div style="font-size: 13px; font-weight: 800; color: #166534; margin-top: 2px;">
                            {{ $toPersian($record->dynamic_responses_count) }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 10px; color: #64748b;">بازدیدها</div>
                        <div style="font-size: 13px; font-weight: 700; color: #334155; margin-top: 2px;">
                            {{ $toPersian($record->views_count) }}
                        </div>
                    </div>
                </div>

                <!-- فوتر و کلیدهای اقدام سریع (با آیکون‌های برداری SVG، بدون ایموجی) -->
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px; padding-top: 8px; border-top: 1px solid #f1f5f9; font-size: 11px;">
                    
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <button type="button" 
                                wire:click="previewTemplate({{ $record->id }})" 
                                title="پیش‌نمایش فرم پرسشنامه"
                                style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #0284c7; background: #e0f2fe; border: 1px solid #bae6fd; padding: 5px 10px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                            <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span>پیش‌نمایش</span>
                        </button>

                        <a href="{{ \App\Filament\Resources\BriefTemplates\BriefTemplateResource::getUrl('edit', ['record' => $record]) }}" 
                           title="ویرایش فرم پرسشنامه"
                           style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #4338ca; text-decoration: none; padding: 5px 10px; border-radius: 6px; background: #eef2ff; border: 1px solid #c7d2fe; transition: all 0.2s;">
                            <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span>ویرایش</span>
                        </a>
                    </div>

                    <div style="display: flex; align-items: center; gap: 4px;">
                        <button type="button" 
                                wire:click="toggleActive({{ $record->id }})" 
                                title="{{ $record->is_active ? 'غیرفعال‌سازی' : 'فعال‌سازی' }}"
                                style="padding: 6px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; color: {{ $record->is_active ? '#dc2626' : '#16a34a' }}; cursor: pointer;">
                            <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                            </svg>
                        </button>

                        <button type="button" 
                                wire:click="deleteTemplate({{ $record->id }})" 
                                wire:confirm="آیا از حذف این پرسشنامه اطمینان دارید؟" 
                                title="حذف"
                                style="padding: 6px; border-radius: 6px; border: 1px solid #fecdd3; background: #ffe4e6; color: #be123c; cursor: pointer;">
                            <svg style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>

                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; padding: 32px 16px; text-align: center; color: #475569; font-size: 13px; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
                هیچ پرسشنامه‌ای مطابق فیلتر جاری یافت نشد.
            </div>
        @endforelse

        <!-- کارت اختصاصی «+ ساخت پرسشنامه جدید» -->
        <a href="{{ \App\Filament\Resources\BriefTemplates\BriefTemplateResource::getUrl('create') }}" 
           style="background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 24px 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; text-decoration: none; cursor: pointer; transition: all 0.2s; min-height: 180px;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 22px; height: 22px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: #4338ca;">ساخت پرسشنامه جدید</span>
        </a>

    </div>

</div>

@php
    $toPersian = fn($number) => str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], (string) number_format($number ?? 0));
@endphp

<div class="q-grid-wrapper" dir="rtl">
    <style>
        .q-grid-wrapper {
            font-family: inherit;
            width: 100%;
            box-sizing: border-box;
        }

        /* Cards Grid Layout */
        .q-cards-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.25rem;
        }
        @media (min-width: 640px) {
            .q-cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .q-cards-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (min-width: 1280px) {
            .q-cards-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Unified Questionnaire Card Item */
        .q-card-item {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            overflow: hidden;
            display: flex;
            height: 195px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .dark .q-card-item {
            background-color: #0f172a;
            border-color: #1e293b;
        }

        .q-card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }
        .dark .q-card-item:hover {
            border-color: #334155;
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.3);
        }

        /* Right Side Main Title Thumbnail Area (Pastel Background Tint) */
        .q-card-title-col {
            flex: 1;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
        }
        .dark .q-card-title-col {
            background-color: #0f172a;
        }

        .q-card-thumbnail-box {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0.09) 100%);
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            text-align: center;
            box-sizing: border-box;
            border: 1px solid rgba(99, 102, 241, 0.15);
            transition: all 0.2s ease;
        }
        .dark .q-card-thumbnail-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(99, 102, 241, 0.18) 100%);
            border-color: rgba(99, 102, 241, 0.25);
        }

        .q-card-title-text {
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            line-height: 1.5;
        }
        .dark .q-card-title-text {
            color: #e2e8f0;
        }

        /* Left Side Stats / Action Panel */
        .q-card-stats-col {
            width: 125px;
            background-color: #ffffff;
            border-right: 1px solid #f1f5f9;
            padding: 0.65rem 0.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
            transition: background-color 0.2s ease;
        }
        .dark .q-card-stats-col {
            background-color: #0f172a;
            border-right-color: #1e293b;
        }

        .q-stat-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.15rem;
        }

        .q-stat-label {
            font-size: 0.675rem;
            color: #64748b;
            font-weight: 400;
        }
        .dark .q-stat-label {
            color: #94a3b8;
        }

        .q-stat-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
        }
        .dark .q-stat-value {
            color: #e2e8f0;
        }

        .q-status-badge {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.125rem 0.45rem;
            border-radius: 9999px;
            display: inline-block;
        }
        .q-status-active {
            background-color: #ecfdf5;
            color: #059669;
        }
        .dark .q-status-active {
            background-color: rgba(6, 95, 70, 0.25);
            color: #34d399;
        }
        .q-status-inactive {
            background-color: #fef2f2;
            color: #dc2626;
        }
        .dark .q-status-inactive {
            background-color: rgba(153, 27, 27, 0.25);
            color: #f87171;
        }

        /* Organized Action Menu Overlay */
        .q-actions-container {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            width: 100%;
            height: 100%;
            justify-content: space-between;
            padding: 0.25rem 0.15rem;
            text-align: right;
            box-sizing: border-box;
        }

        .q-action-item-btn {
            background: transparent;
            border: none;
            font-size: 0.75rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            text-align: right;
            padding: 0.3rem 0.4rem;
            width: 100%;
            border-radius: 0.45rem;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none !important;
            box-sizing: border-box;
        }
        .dark .q-action-item-btn {
            color: #cbd5e1;
        }
        .q-action-item-btn:hover {
            color: var(--primary-600, #4f46e5);
            background-color: #f1f5f9;
        }
        .dark .q-action-item-btn:hover {
            color: var(--primary-400, #818cf8);
            background-color: #1e293b;
        }

        /* Strict Sizing for SVG Icons inside action buttons */
        .q-action-item-btn svg {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            min-height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            flex-shrink: 0 !important;
            display: inline-block !important;
        }

        .q-action-item-danger {
            color: #e11d48 !important;
        }
        .dark .q-action-item-danger {
            color: #fb7185 !important;
        }
        .q-action-item-danger:hover {
            background-color: #fef2f2 !important;
        }
        .dark .q-action-item-danger:hover {
            background-color: rgba(153, 27, 27, 0.2) !important;
        }

        /* Small, Thin, Sleek Three Dots Button */
        .q-card-menu-btn {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #94a3b8;
            border-radius: 0.45rem;
            width: 26px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .dark .q-card-menu-btn {
            background-color: #1e293b;
            border-color: #334155;
            color: #64748b;
        }
        .q-card-menu-btn:hover, .q-card-menu-btn.active {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #475569;
        }
        .dark .q-card-menu-btn:hover, .dark .q-card-menu-btn.active {
            background-color: #334155;
            border-color: #475569;
            color: #e2e8f0;
        }

        /* Dedicated "Create New Questionnaire" Card (Filament Theme System) */
        .q-create-card {
            background-color: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 1.25rem;
            height: 195px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
        .dark .q-create-card {
            background-color: #0f172a;
            border-color: #334155;
        }

        .q-create-card:hover {
            border-color: var(--primary-500, #6366f1);
            background-color: #f8fafc;
            transform: translateY(-2px);
        }
        .dark .q-create-card:hover {
            border-color: var(--primary-400, #818cf8);
            background-color: #1e293b;
        }
    </style>

    <!-- Cards Grid -->
    <div class="q-cards-grid">
        @forelse($templates as $record)
            <div 
                x-data="{ open: false }" 
                @mouseenter="open = true" 
                @mouseleave="open = false" 
                class="q-card-item" 
                id="questionnaire-card-{{ $record->id }}"
            >
                <!-- Right Side Main Title Thumbnail Area (First in HTML order -> Right in RTL) -->
                <div class="q-card-title-col">
                    <div class="q-card-thumbnail-box">
                        <span class="q-card-title-text">
                            {{ $record->name }}
                        </span>
                    </div>
                </div>

                <!-- Left Side Stats / Action Panel (Second in HTML order -> Left in RTL) -->
                <div class="q-card-stats-col">
                    <!-- Default Stats Display (when not hovering/clicking) -->
                    <div x-show="!open" class="flex flex-col h-full justify-between items-center py-0.5 w-full">
                        <div class="q-stat-unit">
                            <span class="q-stat-label">بازدید</span>
                            <span class="q-stat-value">{{ $toPersian($record->views_count ?? 0) }}</span>
                        </div>

                        <div class="q-stat-unit">
                            <span class="q-stat-label">پاسخ</span>
                            <span class="q-stat-value">{{ $toPersian($record->dynamic_responses_count ?? 0) }}</span>
                        </div>

                        <div class="q-stat-unit">
                            <span class="q-stat-label">سوالات</span>
                            <span class="q-stat-value">{{ $toPersian($record->questions_count ?? 0) }}</span>
                        </div>

                        <div class="my-0.5">
                            @if($record->is_active)
                                <span class="q-status-badge q-status-active">فعال</span>
                            @else
                                <span class="q-status-badge q-status-inactive">غیرفعال</span>
                            @endif
                        </div>

                        <button 
                            type="button" 
                            @click.stop="open = !open" 
                            class="q-card-menu-btn" 
                            title="گزینه‌ها"
                        >
                            <svg style="width: 14px !important; height: 14px !important;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Organized Action List Display (on hover / click - clean spacing) -->
                    <div x-show="open" x-transition.opacity class="q-actions-container">
                        <button 
                            type="button" 
                            wire:click="previewTemplate({{ $record->id }})" 
                            class="q-action-item-btn"
                        >
                            <svg class="text-info-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>پیش‌نمایش</span>
                        </button>

                        <a 
                            href="{{ \App\Filament\Resources\BriefTemplates\BriefTemplateResource::getUrl('edit', ['record' => $record]) }}" 
                            class="q-action-item-btn"
                        >
                            <svg class="text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>ویرایش</span>
                        </a>

                        <button 
                            type="button" 
                            wire:click="toggleActive({{ $record->id }})" 
                            class="q-action-item-btn {{ $record->is_active ? 'q-action-item-danger' : 'text-emerald-600' }}"
                        >
                            @if($record->is_active)
                                <svg class="text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>غیرفعال‌سازی</span>
                            @else
                                <svg class="text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                <span>فعال‌سازی</span>
                            @endif
                        </button>

                        <button 
                            type="button" 
                            wire:click="deleteTemplate({{ $record->id }})" 
                            wire:confirm="آیا از حذف این پرسشنامه اطمینان دارید؟" 
                            class="q-action-item-btn q-action-item-danger"
                        >
                            <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>حذف</span>
                        </button>

                        <div class="w-full flex justify-center pt-0.5 border-t border-gray-100 dark:border-gray-800">
                            <button 
                                type="button" 
                                @click.stop="open = false" 
                                class="q-card-menu-btn active"
                                title="بستن"
                            >
                                <svg style="width: 14px !important; height: 14px !important;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-gray-300 dark:border-gray-800">
                هیچ پرسشنامه‌ای یافت نشد.
            </div>
        @endforelse

        <!-- Dedicated "Create New Questionnaire" Card (Filament Theme System) -->
        <a href="{{ \App\Filament\Resources\BriefTemplates\BriefTemplateResource::getUrl('create') }}" class="q-create-card group">
            <div class="w-11 h-11 rounded-2xl bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 border border-primary-200/60 dark:border-primary-800/50 flex items-center justify-center shadow-xs transition-all group-hover:scale-105 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/60">
                <svg style="width: 20px !important; height: 20px !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="text-xs md:text-sm font-medium text-primary-600 dark:text-primary-400 transition-colors">ساخت پرسشنامه جدید</span>
        </a>
    </div>
</div>

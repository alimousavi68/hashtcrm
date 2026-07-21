<div class="brief-preview-wrapper">
    <style>
        .brief-preview-wrapper {
            direction: rtl;
            text-align: right;
            font-family: inherit;
            color: #1e293b;
            width: 100%;
            box-sizing: border-box;
        }
        @media (prefers-color-scheme: dark) {
            .brief-preview-wrapper {
                color: #f1f5f9;
            }
        }

        /* SVG Sizing Fix */
        .brief-preview-wrapper svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            display: inline-block !important;
            vertical-align: middle;
            flex-shrink: 0;
        }

        /* Notice banner */
        .brief-notice-box {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.85rem;
            color: #1e3a8a;
            margin-bottom: 1.25rem;
        }
        .dark .brief-notice-box {
            background-color: rgba(30, 58, 138, 0.25);
            border-color: #1e40af;
            color: #93c5fd;
        }

        /* Header meta info bar */
        .brief-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
            font-size: 0.825rem;
            color: #64748b;
        }
        .dark .brief-meta-bar {
            border-color: #334155;
            color: #94a3b8;
        }

        /* Badges */
        .brief-badge-purple {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: #f3e8ff;
            color: #6b21a8;
            border: 1px solid #d8b4fe;
        }
        .dark .brief-badge-purple {
            background-color: rgba(107, 33, 168, 0.3);
            color: #e9d5ff;
            border-color: #7e22ce;
        }

        .brief-badge-blue {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .brief-badge-rose {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            background-color: #ffe4e6;
            color: #be123c;
            border: 1px solid #fecdd3;
        }
        .dark .brief-badge-rose {
            background-color: rgba(190, 18, 60, 0.25);
            color: #fda4af;
            border-color: #9f1239;
        }

        /* Progress track */
        .brief-progress-container {
            margin-bottom: 1.5rem;
        }
        .brief-progress-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .dark .brief-progress-header {
            color: #cbd5e1;
        }
        .brief-progress-track {
            width: 100%;
            height: 0.65rem;
            background-color: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
        }
        .dark .brief-progress-track {
            background-color: #334155;
        }
        .brief-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            border-radius: 9999px;
            transition: width 0.4s ease-out;
        }

        /* Card styling */
        .brief-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 14px -2px rgba(0,0,0,0.06);
            margin-bottom: 1.25rem;
        }
        .dark .brief-card {
            background-color: #0f172a;
            border-color: #1e293b;
            box-shadow: 0 4px 16px -2px rgba(0,0,0,0.4);
        }

        .brief-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .brief-question-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.5;
            margin: 0;
        }
        .dark .brief-question-title {
            color: #f8fafc;
        }

        /* Inputs */
        .brief-input-text, .brief-textarea, .brief-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 0.925rem;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
            font-family: inherit;
        }
        .brief-input-text:focus, .brief-textarea:focus, .brief-select:focus {
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .dark .brief-input-text, .dark .brief-textarea, .dark .brief-select {
            border-color: #334155;
            background-color: #1e293b;
            color: #f8fafc;
        }
        .dark .brief-input-text:focus, .dark .brief-textarea:focus, .dark .brief-select:focus {
            border-color: #60a5fa;
            background-color: #0f172a;
        }

        /* Radio grid */
        .brief-radio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }
        .brief-radio-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1.1rem;
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            font-size: 0.875rem;
            user-select: none;
        }
        .dark .brief-radio-option {
            border-color: #1e293b;
            background-color: rgba(30, 41, 59, 0.5);
        }
        .brief-radio-option.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
            color: #1e40af;
        }
        .dark .brief-radio-option.selected {
            border-color: #60a5fa;
            background-color: rgba(30, 58, 138, 0.35);
            color: #bfdbfe;
        }

        .brief-radio-dot {
            width: 1.15rem;
            height: 1.15rem;
            border-radius: 9999px;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .brief-radio-option.selected .brief-radio-dot {
            border-color: #2563eb;
            background-color: #2563eb;
            color: #ffffff;
        }

        /* Upload zone */
        .brief-upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 0.85rem;
            padding: 1.5rem;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .brief-upload-zone:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .dark .brief-upload-zone {
            border-color: #334155;
            background-color: rgba(30, 41, 59, 0.4);
        }

        /* Nav bar */
        .brief-nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
        }
        .brief-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.35rem;
            border-radius: 0.75rem;
            font-size: 0.825rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .brief-btn-secondary {
            background-color: #f1f5f9;
            color: #334155;
        }
        .brief-btn-secondary:hover {
            background-color: #e2e8f0;
        }
        .dark .brief-btn-secondary {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        .brief-btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }
        .brief-btn-primary:hover {
            background-color: #1d4ed8;
        }
        .brief-btn-success {
            background-color: #059669;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }
        .brief-btn-success:hover {
            background-color: #047857;
        }

        /* Help accordion */
        .brief-help-accordion {
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.8rem;
        }
        .dark .brief-help-accordion {
            border-top-color: #1e293b;
        }
        .brief-help-summary {
            cursor: pointer;
            color: #2563eb;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .dark .brief-help-summary {
            color: #60a5fa;
        }
        .brief-help-content {
            margin-top: 0.5rem;
            padding: 0.85rem 1rem;
            border-radius: 0.65rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            line-height: 1.6;
        }
        .dark .brief-help-content {
            background-color: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }
    </style>

    @if(filled($record->guide_notice))
        <div class="brief-notice-box">
            <x-heroicon-o-information-circle />
            <div>
                <h4 style="font-weight: 700; font-size: 0.875rem; margin: 0 0 0.25rem 0;">راهنمای کارفرما:</h4>
                <p style="font-size: 0.875rem; margin: 0; opacity: 0.95; line-height: 1.6;">{{ $record->guide_notice }}</p>
            </div>
        </div>
    @endif

    <div class="brief-meta-bar">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <x-heroicon-o-document-text style="color: #2563eb;" />
            <span>الگو: <strong>{{ $record->name }}</strong></span>
        </div>
        <div>
            @if($record->wizard_mode)
                <span class="brief-badge-purple">
                    ⚡ حالت تک‌سوال (Typeform Slider)
                </span>
            @else
                <span class="brief-badge-blue">
                    📋 حالت فرم یکپارچه
                </span>
            @endif
        </div>
    </div>

    @if(empty($record->schema) || count($record->schema) === 0)
        <div style="text-align: center; padding: 3rem 1rem; color: #64748b;">
            <x-heroicon-o-document-magnifying-glass style="width: 3rem !important; height: 3rem !important; margin: 0 auto 0.75rem auto; opacity: 0.5;" />
            <p style="font-weight: 700; margin: 0;">هیچ فیلدی در این الگوی بریف تعریف نشده است.</p>
        </div>
    @else
        @php
            $blocks = array_values($record->schema);
            $totalBlocks = count($blocks);
        @endphp

        @if($record->wizard_mode)
            {{-- ─── حالت تک‌سوال مدرن (Typeform Style Slider) ─────────────────────── --}}
            <div x-data="{ 
                currentStep: 0, 
                totalSteps: {{ $totalBlocks }},
                answers: {},
                radioSelections: {},
                isSubmitted: false
            }">

                {{-- Progress Bar --}}
                <div class="brief-progress-container">
                    <div class="brief-progress-header">
                        <span>سوال <span x-text="currentStep + 1"></span> از {{ $totalBlocks }}</span>
                        <span x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '% تکمیل شده'"></span>
                    </div>
                    <div class="brief-progress-track">
                        <div class="brief-progress-fill" :style="'width: ' + Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></div>
                    </div>
                </div>

                {{-- Question Cards --}}
                <div>
                    @foreach($blocks as $index => $block)
                        @php
                            $type = $block['type'] ?? '';
                            $data = $block['data'] ?? [];
                            $label = $data['label'] ?? $data['title'] ?? 'بدون عنوان';
                            $isRequired = !empty($data['required']);
                            $isEssential = !empty($data['is_essential']);
                            $placeholder = $data['placeholder'] ?? '';
                            $helpContent = $data['help_content'] ?? '';
                            $helpDefaultOpen = !empty($data['help_default_open']);
                            $options = array_filter(array_map('trim', explode(',', $data['options'] ?? '')));
                        @endphp

                        <div x-show="currentStep === {{ $index }}" 
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="brief-card">
                            
                            @if($type === 'instruction_block')
                                <div style="padding: 1.25rem; border-radius: 0.85rem; background-color: #fffbeb; border: 1px solid #fde68a; color: #78350f;">
                                    @if(filled($data['title'] ?? null))
                                        <h4 style="font-weight: 800; font-size: 1.1rem; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem; color: #b45309;">
                                            <x-heroicon-o-sparkles style="color: #d97706;" />
                                            {{ $data['title'] }}
                                        </h4>
                                    @endif
                                    @if(filled($data['content'] ?? null))
                                        <div style="font-size: 0.875rem; line-height: 1.6;">
                                            {!! $data['content'] !!}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="brief-card-header">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                            <span style="padding: 0.15rem 0.5rem; border-radius: 0.375rem; background-color: #dbeafe; color: #1e40af; font-size: 0.75rem; font-weight: 700;">
                                                سوال {{ $index + 1 }}
                                            </span>
                                            @if($isRequired)
                                                <span style="font-size: 0.75rem; color: #e11d48; font-weight: 700;">* پاسخ الزامی است</span>
                                            @endif
                                        </div>
                                        <h3 class="brief-question-title">
                                            {{ $label }}
                                        </h3>
                                    </div>

                                    @if($isEssential)
                                        <span class="brief-badge-rose">
                                            🚨 مدرک ضروری پروژه
                                        </span>
                                    @endif
                                </div>

                                {{-- Field Inputs --}}
                                <div style="margin-top: 1rem;">
                                    @if($type === 'text_input')
                                        <input type="text" 
                                               x-model="answers['field_{{ $index }}']"
                                               placeholder="{{ $placeholder ?: 'پاسخ خود را بنویسید...' }}" 
                                               class="brief-input-text" />

                                    @elseif($type === 'textarea')
                                        <textarea rows="4" 
                                                  x-model="answers['field_{{ $index }}']"
                                                  placeholder="{{ $placeholder ?: 'توضیحات کامل خود را بفرمایید...' }}" 
                                                  class="brief-textarea"></textarea>

                                    @elseif($type === 'select')
                                        <select x-model="answers['field_{{ $index }}']" class="brief-select">
                                            <option value="">یک گزینه را انتخاب نمایید...</option>
                                            @foreach($options as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>

                                    @elseif($type === 'radio_choice')
                                        <div class="brief-radio-grid">
                                            @foreach($options as $optIndex => $opt)
                                                <div x-on:click="radioSelections['field_{{ $index }}'] = '{{ $opt }}'"
                                                     :class="radioSelections['field_{{ $index }}'] === '{{ $opt }}' ? 'selected' : ''"
                                                     class="brief-radio-option">
                                                    <span>{{ $opt }}</span>
                                                    <div class="brief-radio-dot">
                                                        <template x-if="radioSelections['field_{{ $index }}'] === '{{ $opt }}'">
                                                            <x-heroicon-o-check style="width: 0.75rem !important; height: 0.75rem !important; color: white;" />
                                                        </template>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($type === 'file_upload')
                                        <div class="brief-upload-zone" onclick="document.getElementById('preview_file_{{ $index }}').click()">
                                            <x-heroicon-o-cloud-arrow-up style="width: 2.5rem !important; height: 2.5rem !important; color: #3b82f6; margin-bottom: 0.5rem;" />
                                            <p style="font-size: 0.875rem; font-weight: 700; margin: 0 0 0.25rem 0;">فایل را جهت آپلود انتخاب کنید یا اینجا بکشید</p>
                                            <p style="font-size: 0.75rem; color: #64748b; margin: 0;">فرمت‌های مجاز: PDF, ZIP, PNG, JPG (حداکثر ۲۰ مگابایت)</p>
                                            <input type="file" class="hidden" id="preview_file_{{ $index }}" style="display:none;" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Help Section Accordion --}}
                                @if(filled($helpContent))
                                    <details class="brief-help-accordion" @if($helpDefaultOpen) open @endif>
                                        <summary class="brief-help-summary">
                                            <x-heroicon-o-light-bulb style="color: #f59e0b;" />
                                            <span>مشاهده راهنما و توضیحات فیلد</span>
                                        </summary>
                                        <div class="brief-help-content">
                                            {!! $helpContent !!}
                                        </div>
                                    </details>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Action Bar --}}
                <div class="brief-nav-bar">
                    <button type="button" 
                            x-on:click="if(currentStep > 0) currentStep--"
                            :disabled="currentStep === 0"
                            :style="currentStep === 0 ? 'opacity: 0.4; cursor: not-allowed;' : ''"
                            class="brief-btn brief-btn-secondary">
                        <x-heroicon-o-arrow-right />
                        <span>سوال قبلی</span>
                    </button>

                    <template x-if="currentStep < totalSteps - 1">
                        <button type="button" 
                                x-on:click="currentStep++"
                                class="brief-btn brief-btn-primary">
                            <span>سوال بعدی</span>
                            <x-heroicon-o-arrow-left />
                        </button>
                    </template>

                    <template x-if="currentStep === totalSteps - 1">
                        <button type="button" 
                                x-on:click="isSubmitted = true"
                                class="brief-btn brief-btn-success">
                            <x-heroicon-o-check-circle />
                            <span>ثبت اطلاعات (حالت تست)</span>
                        </button>
                    </template>
                </div>

                <div x-show="isSubmitted" x-cloak style="margin-top: 1rem; padding: 0.85rem; border-radius: 0.75rem; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.825rem; font-weight: 700; text-align: center;">
                    ✨ تست ارسال موفقیت‌آمیز بود! پاسخ‌ها در حالت پیش‌نمایش به صورت پویا ثبت گردیدند.
                </div>
            </div>

        @else
            {{-- ─── حالت فرم یکپارچه (Single Page All Questions) ───────────────────── --}}
            <div>
                @foreach($blocks as $index => $block)
                    @php
                        $type = $block['type'] ?? '';
                        $data = $block['data'] ?? [];
                        $label = $data['label'] ?? $data['title'] ?? 'بدون عنوان';
                        $isRequired = !empty($data['required']);
                        $isEssential = !empty($data['is_essential']);
                        $placeholder = $data['placeholder'] ?? '';
                        $helpContent = $data['help_content'] ?? '';
                        $helpDefaultOpen = !empty($data['help_default_open']);
                        $options = array_filter(array_map('trim', explode(',', $data['options'] ?? '')));
                    @endphp

                    @if($type === 'instruction_block')
                        <div style="padding: 1.25rem; border-radius: 0.85rem; background-color: #fffbeb; border: 1px solid #fde68a; color: #78350f; margin-bottom: 1.25rem;">
                            @if(filled($data['title'] ?? null))
                                <h4 style="font-weight: 800; font-size: 1.05rem; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem; color: #b45309;">
                                    <x-heroicon-o-sparkles style="color: #d97706;" />
                                    {{ $data['title'] }}
                                </h4>
                            @endif
                            @if(filled($data['content'] ?? null))
                                <div style="font-size: 0.875rem; line-height: 1.6;">
                                    {!! $data['content'] !!}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="brief-card">
                            <div class="brief-card-header">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                        <span style="padding: 0.15rem 0.5rem; border-radius: 0.375rem; background-color: #dbeafe; color: #1e40af; font-size: 0.75rem; font-weight: 700;">
                                            سوال {{ $index + 1 }}
                                        </span>
                                        @if($isRequired)
                                            <span style="font-size: 0.75rem; color: #e11d48; font-weight: 700;">* اجباری</span>
                                        @endif
                                    </div>
                                    <h3 class="brief-question-title">
                                        {{ $label }}
                                    </h3>
                                </div>

                                @if($isEssential)
                                    <span class="brief-badge-rose">
                                        🚨 مدرک ضروری پروژه
                                    </span>
                                @endif
                            </div>

                            <div style="margin-top: 1rem;">
                                @if($type === 'text_input')
                                    <input type="text" placeholder="{{ $placeholder ?: 'پاسخ شما...' }}" class="brief-input-text" />
                                @elseif($type === 'textarea')
                                    <textarea rows="3" placeholder="{{ $placeholder ?: 'توضیحات شما...' }}" class="brief-textarea"></textarea>
                                @elseif($type === 'select')
                                    <select class="brief-select">
                                        <option value="">یک گزینه را انتخاب کنید...</option>
                                        @foreach($options as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @elseif($type === 'radio_choice')
                                    <div class="brief-radio-grid">
                                        @foreach($options as $optIndex => $opt)
                                            <label class="brief-radio-option">
                                                <span>{{ $opt }}</span>
                                                <input type="radio" name="preview_radio_{{ $index }}" style="width: 1.15rem; height: 1.15rem;" />
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($type === 'file_upload')
                                    <div class="brief-upload-zone" onclick="document.getElementById('preview_file_{{ $index }}').click()">
                                        <x-heroicon-o-cloud-arrow-up style="width: 2rem !important; height: 2rem !important; color: #3b82f6; margin-bottom: 0.35rem;" />
                                        <p style="font-size: 0.85rem; font-weight: 700; margin: 0;">محل آپلود فایل (کلیک کنید)</p>
                                    </div>
                                @endif

                                @if(filled($helpContent))
                                    <details class="brief-help-accordion" @if($helpDefaultOpen) open @endif>
                                        <summary class="brief-help-summary">
                                            <x-heroicon-o-light-bulb style="color: #f59e0b;" />
                                            <span>مشاهده راهنما و توضیحات این فیلد</span>
                                        </summary>
                                        <div class="brief-help-content">
                                            {!! $helpContent !!}
                                        </div>
                                    </details>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                <div style="margin-top: 1.5rem;">
                    <button type="button" class="brief-btn brief-btn-success" style="width: 100%; justify-content: center; padding: 0.85rem;">
                        <x-heroicon-o-check-circle />
                        <span>ثبت نهایی فرم بریف (حالت آزمایشی)</span>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>

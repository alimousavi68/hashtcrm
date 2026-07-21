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

        /* Radio & Checkbox grid */
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
        .dark .brief-btn-secondary {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        .brief-btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }
        .brief-btn-success {
            background-color: #059669;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
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
                    ⚡ حالت ویزاردی دسته‌بندی‌شده ۶‌گانه
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
            <p style="font-weight: 700; margin: 0;">هیچ فیلدی در این الگوی پرسشنامه تعریف نشده است.</p>
        </div>
    @else
        @php
            $blocks = array_values($record->schema);
            $totalBlocks = count($blocks);
        @endphp

        <div>
            @foreach($blocks as $index => $block)
                @php
                    $type = $block['type'] ?? '';
                    $data = $block['data'] ?? [];
                    $label = $data['label'] ?? $data['title'] ?? 'بدون عنوان';
                    $stepTitle = $data['step_title'] ?? null;
                    $isRequired = !empty($data['required']);
                    $isEssential = !empty($data['is_essential']);
                    $placeholder = $data['placeholder'] ?? '';
                    $helpContent = $data['help_content'] ?? '';
                    $helpDefaultOpen = !empty($data['help_default_open']);
                    $options = array_filter(array_map('trim', explode(',', $data['options'] ?? '')));
                    $subfields = array_filter(array_map('trim', explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی')));
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
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                                    <span style="padding: 0.15rem 0.5rem; border-radius: 0.375rem; background-color: #dbeafe; color: #1e40af; font-size: 0.75rem; font-weight: 700;">
                                        سوال {{ $index + 1 }}
                                    </span>
                                    @if(filled($stepTitle))
                                        <span class="brief-badge-purple">
                                            📌 {{ $stepTitle }}
                                        </span>
                                    @endif
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

                            @elseif($type === 'url_input')
                                <div style="position: relative;">
                                    <input type="url" placeholder="{{ $placeholder ?: 'https://example.com' }}" class="brief-input-text" style="padding-left: 5rem;" />
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 0.75rem; color: #3b82f6; font-weight: 700;">https://</span>
                                </div>

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

                            @elseif($type === 'checkboxes')
                                <div class="brief-radio-grid">
                                    @foreach($options as $optIndex => $opt)
                                        <label class="brief-radio-option">
                                            <span>{{ $opt }}</span>
                                            <input type="checkbox" name="preview_check_{{ $index }}[]" style="width: 1.15rem; height: 1.15rem;" />
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($type === 'repeater')
                                <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; background-color: #f8fafc;">
                                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="text" placeholder="{{ $placeholder ?: 'آیتم ۱...' }}" class="brief-input-text" />
                                    </div>
                                    <button type="button" class="brief-btn brief-btn-secondary" style="font-size: 0.75rem; padding: 0.4rem 0.85rem;">
                                        + افزودن آیتم جدید
                                    </button>
                                </div>

                            @elseif($type === 'input_group')
                                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                                    @foreach($subfields as $sub)
                                        <div>
                                            <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">{{ $sub }}</label>
                                            <input type="text" placeholder="{{ $sub }}..." class="brief-input-text" />
                                        </div>
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
                    @endif
                @endforeach
            </div>
        @endif
</div>

<x-filament-panels::page>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        .vazir-font, .fi-body, .fi-page, .fi-header, .fi-ta, .fi-fo {
            font-family: 'Vazirmatn', sans-serif !important;
        }
        .custom-input {
            transition: all 0.2s ease-in-out;
        }
        .custom-input:focus {
            border-color: rgb(var(--primary-600)) !important;
            box-shadow: 0 0 0 3px rgba(var(--primary-600), 0.15) !important;
        }
        .tab-btn {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    <div class="space-y-6 vazir-font">
        @if(!$selectedProjectId)
            <!-- STATE A: Projects Overview Grid -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">پروژه‌های شما</h3>
                @if(count($projects) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($projects as $p)
                            @php
                                $statusInfo = $statuses[$p->status] ?? ['label' => 'نامشخص', 'percent' => 0];
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm p-6 hover:shadow-md transition-shadow flex flex-col justify-between gap-6">
                                <div>
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $p->title }}</h4>
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-primary-50 dark:bg-primary-950/30 text-primary-700 dark:text-primary-400 border border-primary-200 dark:border-primary-800">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">تاریخ ثبت پروژه: {{ \Illuminate\Support\Carbon::parse($p->created_at)->format('Y/m/d') }}</p>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs font-semibold text-gray-700 dark:text-gray-300">
                                        <span>میزان پیشرفت کلی</span>
                                        <span>{{ $statusInfo['percent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-150 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $statusInfo['percent'] }}%"></div>
                                    </div>
                                </div>

                                <button wire:click="selectProject({{ $p->id }})" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center justify-center gap-2">
                                    ورود به پنل مدیریت پروژه
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-12 bg-white rounded-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700 text-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h-3.88l-.512-2H8.392l-.513 2H4"/></svg>
                        <p class="text-sm text-gray-500 dark:text-gray-450">هیچ پروژه‌ای برای حساب کاربری شما یافت نشد.</p>
                    </div>
                @endif
            </div>

        @else
            <!-- STATE B: Selected Project Workspace -->
            <div class="flex items-center justify-between">
                <button wire:click="backToProjects" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-850 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    بازگشت به لیست پروژه‌ها
                </button>
                <div class="text-xs text-gray-500">پروژه فعال: <span class="font-bold text-gray-900 dark:text-white">{{ $project->title }}</span></div>
            </div>

            <!-- Project Details Header -->
            <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-xs text-gray-500">میزکار اختصاصی پروژه</p>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $project->title }}</h2>
                    </div>
                    <div class="px-4 py-1.5 bg-primary-50 dark:bg-primary-950/30 text-primary-700 dark:text-primary-400 rounded-full font-medium text-xs border border-primary-200 dark:border-primary-800">
                        وضعیت: {{ $statusLabel }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        <span>میزان پیشرفت پروژه</span>
                        <span>{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-primary-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Workspace Navigation Tabs (Emoji-free) -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 gap-1 overflow-x-auto">
                <button wire:click="setActiveTab('roadmap')" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 whitespace-nowrap flex items-center gap-1.5 {{ $activeTab === 'roadmap' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 012.447 15.5V5.5a2 2 0 011.106-1.789L9 1m0 19v-9m0 9l5.447-2.724A2 2 0 0019.553 15.5V5.5a2 2 0 00-1.106-1.789L15 1m-6 9V1m0 9l6-3.333"/></svg>
                    نقشه راه فازها
                </button>
                <button wire:click="setActiveTab('finance')" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 whitespace-nowrap flex items-center gap-1.5 {{ $activeTab === 'finance' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    قرارداد و امور مالی
                </button>
                <button wire:click="setActiveTab('brief')" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 whitespace-nowrap flex items-center gap-1.5 {{ $activeTab === 'brief' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    بریف و دسترسی‌ها
                </button>
                <button wire:click="setActiveTab('demo')" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 whitespace-nowrap flex items-center gap-1.5 {{ $activeTab === 'demo' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    بازنگری دمو
                </button>
                <button wire:click="setActiveTab('handover')" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 whitespace-nowrap flex items-center gap-1.5 {{ $activeTab === 'handover' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0h-4m0 0v13m0 0h12"/></svg>
                    بسته تحویل نهایی
                </button>
            </div>

            <!-- Tab Content -->
            <div class="mt-2">
                @if($activeTab === 'roadmap')
                    <!-- ROADMAP TAB -->
                    <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-6">مراحل اجرای پروژه</h3>
                        
                        <div class="space-y-0">
                            @foreach($statuses as $key => $info)
                                @php
                                    $isCurrent = $project->status === $key;
                                    $isPassed = $progressPercent >= $info['percent'];
                                @endphp
                                <div class="flex gap-4">
                                    <!-- Indicator Column -->
                                    <div class="flex flex-col items-center shrink-0">
                                        <!-- Circle -->
                                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300
                                            {{ $isCurrent ? 'bg-primary-600 border-primary-600 text-white shadow-md ring-4 ring-primary-100 dark:ring-primary-950/40 font-bold' : ($isPassed ? 'bg-green-500 border-green-500 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-450') }}">
                                            @if($isPassed && !$isCurrent)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <span class="text-xs font-bold">{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        <!-- Vertical Line -->
                                        @if(!$loop->last)
                                            <div class="w-0.5 grow bg-gray-200 dark:bg-gray-700 my-1 min-h-[30px]"></div>
                                        @endif
                                    </div>

                                    <!-- Content Column -->
                                    <div class="pb-6 pt-1 flex-1">
                                        <h4 class="font-bold text-sm transition-colors {{ $isCurrent ? 'text-primary-600 dark:text-primary-400' : ($isPassed ? 'text-gray-900 dark:text-white' : 'text-gray-455') }}">
                                            {{ $info['label'] }}
                                        </h4>
                                        @if($isCurrent)
                                            <p class="text-[11px] text-gray-500 dark:text-gray-450 mt-1 font-medium leading-relaxed">
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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Contract Card -->
                        <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700 space-y-4">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2 border-b pb-3 dark:border-gray-700">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                قرارداد همکاری پروژه
                            </h3>

                            @if($project->contract)
                                @if($project->contract->signed_at)
                                    <div class="p-4 bg-green-50 dark:bg-green-950/20 border border-green-250 rounded-xl text-green-800 dark:text-green-400 space-y-1.5 text-xs">
                                        <div class="flex items-center gap-2 font-bold text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            قرارداد با موفقیت امضای دیجیتال شده است
                                        </div>
                                        <p>امضاکننده: {{ $project->contract->signature_name }} | کد ملی: {{ $project->contract->signature_national_code }}</p>
                                        <p>تاریخ امضا: {{ \Illuminate\Support\Carbon::parse($project->contract->signed_at)->format('Y/m/d H:i') }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl max-h-80 overflow-y-auto text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {!! $renderedContractContent !!}
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl max-h-80 overflow-y-auto text-xs text-gray-750 dark:text-gray-300 leading-relaxed border border-gray-150">
                                        {!! $renderedContractContent !!}
                                    </div>
                                    
                                    <form wire:submit.prevent="signContract" class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <h4 class="font-bold text-xs text-gray-900 dark:text-white">امضای دیجیتال قرارداد</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] text-gray-500 dark:text-gray-400 mb-1">نام و نام خانوادگی امضاکننده</label>
                                                <input type="text" wire:model="sigName" placeholder="مثال: علی رضایی" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                                @error('sigName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-gray-500 dark:text-gray-400 mb-1">کد ملی ده رقمی</label>
                                                <input type="text" wire:model="sigNationalCode" maxlength="10" placeholder="مثال: 0012345678" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                                @error('sigNationalCode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 rounded-xl text-xs shadow-sm transition-colors">
                                            امضا و پذیرش تعهدات قرارداد
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 py-6 text-center">قرارداد همکاری پروژه به زودی توسط مدیریت بارگذاری خواهد شد.</p>
                            @endif
                        </div>

                        <!-- Finance Card -->
                        <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700 space-y-4">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2 border-b pb-3 dark:border-gray-700">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                حسابداری و ثبت فیش‌های بانکی
                            </h3>

                            <div class="p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-250 rounded-xl text-xs text-blue-900 dark:text-blue-450 space-y-1">
                                <p class="font-bold">اطلاعات حساب جهت واریز پیش‌پرداخت / فاکتورها:</p>
                                <p>شماره کارت: ۵۰۲۲-۲۹۱۰-۱۲۳۴-۵۶۷۸</p>
                                <p>به نام: مدیریت شرکت هشت</p>
                                <p class="opacity-75 text-[10px]">پس از واریز مبلغ، تصویر فیش واریزی را جهت تایید و فعال‌سازی مراحل پروژه در فرم زیر ثبت کنید.</p>
                            </div>

                            @if($project->status === 'contract')
                                <form wire:submit.prevent="uploadSlip" class="space-y-4 pt-2">
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">ثبت فیش واریز جدید</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-gray-500 dark:text-gray-400 mb-1">مبلغ واریزی (تومان)</label>
                                            <input type="number" wire:model="paymentAmount" placeholder="مثال: 5000000" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                            @error('paymentAmount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-gray-500 dark:text-gray-400 mb-1">تصویر فیش بانکی</label>
                                            <input type="file" wire:model="bankSlipFile" class="w-full text-xs text-gray-500 dark:text-gray-450" required>
                                            @error('bankSlipFile') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm">
                                        ارسال و ثبت فیش پرداخت
                                    </button>
                                </form>
                            @endif

                            <div class="space-y-2 pt-2">
                                <h4 class="font-bold text-xs text-gray-900 dark:text-white">سوابق تراکنش‌ها</h4>
                                @if($project->payments->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-405">
                                                <tr>
                                                    <th class="px-3 py-2">مبلغ</th>
                                                    <th class="px-3 py-2">وضعیت بررسی</th>
                                                    <th class="px-3 py-2">تاریخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($project->payments as $payment)
                                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-750">
                                                        <td class="px-3 py-2 font-bold text-gray-900 dark:text-white">
                                                            {{ number_format($payment->amount) }} تومان
                                                        </td>
                                                        <td class="px-3 py-2">
                                                            @if($payment->status === 'approved')
                                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-green-50 text-green-700 rounded dark:bg-green-950/30 dark:text-green-400">تایید شده</span>
                                                            @elseif($payment->status === 'rejected')
                                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-red-50 text-red-700 rounded dark:bg-red-950/30 dark:text-red-400">رد شده</span>
                                                            @else
                                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-yellow-50 text-yellow-750 rounded dark:bg-yellow-950/30 dark:text-yellow-400">در انتظار بررسی</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 text-[10px] text-gray-400">
                                                            {{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('Y/m/d') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">تراکنشی یافت نشد.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'brief')
                    <!-- BRIEF TAB -->
                    <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700 space-y-6">
                        @if($project->status === 'brief')
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">تکمیل پرسشنامه بریف پروژه</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">لطفاً مشخصات کسب‌وکار و اطلاعات دسترسی هاست/دامنه خود را به صورت گام به گام تکمیل فرمایید تا فرآیند فنی کار آغاز شود.</p>
                            
                            <div class="bg-amber-50 dark:bg-amber-900/20 p-6 rounded-xl border border-amber-200 dark:border-amber-900/50 text-center space-y-4">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">تکمیل بریف در صفحه اختصاصی</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed">برای راحتی شما و تمرکز بیشتر، فرم بریف پروژه شما در یک صفحه اختصاصی آماده شده است. لطفاً برای شروع فرآیند تکمیل اطلاعات روی دکمه زیر کلیک کنید.</p>
                                <a href="{{ \App\Filament\Client\Pages\CompleteBrief::getUrl() }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                                    ورود به فرم بریف
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </a>
                            </div>
                        @else
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b pb-3 dark:border-gray-700">اطلاعات بریف و دسترسی‌های ثبت‌شده</h3>
                            @if($project->briefAnswer)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl space-y-3 text-xs leading-relaxed">
                                    @if(is_array($project->briefAnswer->dynamic_answers))
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($project->briefAnswer->dynamic_answers as $key => $value)
                                                @if(!is_array($value) && !empty($value))
                                                    <p><span class="font-bold text-gray-500">{{ __($key) }}:</span> {{ $value }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p>اطلاعاتی یافت نشد.</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-6">پرسشنامه بریف برای این پروژه هنوز تکمیل نشده است.</p>
                            @endif

                            @if($project->credential)
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mt-6 mb-2">اطلاعات دسترسی سرور و دامنه (امن و رمزنگاری شده)</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl space-y-2 text-xs">
                                    <p><span class="font-bold text-gray-500">ارائه‌دهنده هاست:</span> {{ $project->credential->host_provider }}</p>
                                    <p><span class="font-bold text-gray-500">آدرس پنل هاست:</span> {{ $project->credential->host_panel_url }}</p>
                                    <p><span class="font-bold text-gray-500">ثبت‌کننده دامنه:</span> {{ $project->credential->domain_provider }}</p>
                                    <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-2 font-medium">نکته امنیتی: به منظور امنیت دارایی‌های شما، کلمات عبور به صورت رمزنگاری شده دوطرفه ذخیره شده و در پنل وب قابل رویت مجدد نیستند.</p>
                                </div>
                            @endif
                        @endif
                    </div>

                @elseif($activeTab === 'demo')
                    <!-- DEMO TAB -->
                    <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    بررسی دمو و پیش‌نمایش پروژه
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">نسخه آزمایشی پروژه خود را بررسی کنید و نظرات خود را برای اصلاح یا تایید نهایی بنویسید.</p>
                            </div>
                        </div>

                        @if($project->status !== 'review' && $project->feedbacks->count() === 0)
                            <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-center border border-dashed border-gray-200 dark:border-gray-850">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">پروژه در حال حاضر در فاز دمو و بازنگری نیست. به محض اتمام توسعه و انتشار دمو توسط تیم فنی، این بخش فعال خواهد شد.</p>
                            </div>
                        @else
                            @if(!$project->demo_url)
                                <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-center border border-dashed border-gray-200 dark:border-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">دموی اولیه پروژه در حال آماده‌سازی است. به محض قرار گرفتن لینک دمو، از طریق همین بخش می‌توانید آن را بررسی کنید.</p>
                                </div>
                            @else
                                <!-- Countdown Timer -->
                                @if($project->feedback_deadline && $project->status === 'review')
                                    <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-250 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="flex items-center gap-2 text-amber-800 dark:text-amber-400">
                                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="font-bold text-xs">مهلت ارسال بازنگری و ثبت فیدبک دمو:</span>
                                        </div>
                                        <div class="flex items-center gap-1.5" style="direction: ltr;" id="countdown-timer" data-deadline="{{ $project->feedback_deadline->toIso8601String() }}">
                                            در حال محاسبه...
                                        </div>
                                    </div>
                                    <script>
                                        (function() {
                                            function updateTimer() {
                                                const el = document.getElementById('countdown-timer');
                                                if (!el) return;
                                                const deadline = new Date(el.getAttribute('data-deadline'));
                                                const now = new Date();
                                                const diff = deadline - now;
                                                if (diff <= 0) {
                                                    el.innerHTML = '<span class="text-xs text-red-650 dark:text-red-400 font-bold">زمان به پایان رسیده است. در حال بروزرسانی صفحه...</span>';
                                                    clearInterval(timerInterval);
                                                    setTimeout(() => {
                                                        window.location.reload();
                                                    }, 2000);
                                                    return;
                                                }
                                                const hours = Math.floor(diff / (1000 * 60 * 60));
                                                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                                
                                                const hStr = `<span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-900 dark:text-amber-300 rounded-lg font-mono font-bold text-sm">${String(hours).padStart(2, '0')}</span><span class="text-[10px] text-amber-700 dark:text-amber-400">h</span>`;
                                                const mStr = `<span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-900 dark:text-amber-300 rounded-lg font-mono font-bold text-sm">${String(minutes).padStart(2, '0')}</span><span class="text-[10px] text-amber-700 dark:text-amber-400">m</span>`;
                                                const sStr = `<span class="px-2.5 py-1 bg-amber-200 dark:bg-amber-900/60 text-amber-950 dark:text-amber-200 rounded-lg font-mono font-bold text-sm animate-pulse">${String(seconds).padStart(2, '0')}</span><span class="text-[10px] text-amber-700 dark:text-amber-400">s</span>`;
                                                
                                                el.innerHTML = `${hStr} : ${mStr} : ${sStr}`;
                                            }
                                            const timerInterval = setInterval(updateTimer, 1000);
                                            updateTimer();
                                        })();
                                    </script>
                                @endif

                                <!-- Demo URL Button -->
                                <div class="flex justify-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <a href="{{ $project->demo_url }}" target="_blank" class="bg-primary-600 hover:bg-primary-500 text-white shadow-sm px-6 py-3 rounded-xl font-bold text-xs flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        مشاهده دمو و پیش‌نمایش زنده سایت
                                    </a>
                                </div>

                                @if($project->status === 'review')
                                    <!-- Feedback Form -->
                                    <div class="space-y-4">
                                        <h4 class="font-bold text-xs text-gray-900 dark:text-white">ثبت نظر یا اعلام مغایرت‌ها:</h4>
                                        <textarea wire:model="feedbackNotes" placeholder="در صورتی که بخش‌های نیاز به اصلاح وجود دارد، جزئیات آن را در این قسمت به صورت گام به گام وارد نمایید..." rows="4" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-3 text-xs text-gray-950 dark:text-white focus:ring-primary-500 focus:border-primary-500"></textarea>
                                        @error('feedbackNotes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <button wire:click="submitFeedback('needs_changes')" class="flex-1 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/30 text-red-700 dark:text-red-400 font-bold py-2.5 rounded-xl text-xs border border-red-200 dark:border-red-900/50 transition-colors">
                                                ثبت نیاز به اصلاحات و تغییرات
                                            </button>
                                            <button wire:click="submitFeedback('approved')" class="flex-1 bg-green-600 hover:bg-green-500 text-white font-bold py-2.5 rounded-xl text-xs shadow transition-colors">
                                                تایید نهایی دمو و ارسال به مرحله بعد
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- History of Feedbacks -->
                                <div class="space-y-3 pt-4 border-t dark:border-gray-700">
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">تاریخچه بازخوردها</h4>
                                    @forelse($project->feedbacks as $fb)
                                        <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-150 text-xs">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-bold">
                                                    وضعیت ثبت شده: 
                                                    @if($fb->status === 'approved')
                                                        <span class="text-green-600">تایید نهایی</span>
                                                    @elseif($fb->status === 'needs_changes')
                                                        <span class="text-red-650">نیاز به تغییرات</span>
                                                    @else
                                                        <span class="text-yellow-600">در انتظار بررسی</span>
                                                    @endif
                                                </span>
                                                <span class="text-[10px] text-gray-400">{{ \Illuminate\Support\Carbon::parse($fb->created_at)->format('Y/m/d H:i') }}</span>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->notes }}</p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-2">هیچ فیدبکی تاکنون ثبت نشده است.</p>
                                    @endforelse
                                </div>
                            @endif
                    </div>
                @elseif($activeTab === 'handover')
                    <!-- HANDOVER TAB -->
                    <div class="p-6 bg-white rounded-2xl shadow-sm dark:bg-gray-800 border border-gray-150 dark:border-gray-700">
                        @if(!$project->is_settled)
                            <!-- Settle Lock Screen -->
                            <div class="flex flex-col items-center justify-center p-12 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-500 border border-amber-200 dark:border-amber-900/50">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">بسته تحویل نهایی قفل است</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md leading-relaxed">
                                    دسترسی به اطلاعات نهایی پروژه، آموزش‌های استفاده از سایت و مشخصات حساس سرور، منوط به تسویه حساب کامل مالی و تایید پرداخت‌ها توسط بخش حسابداری است.
                                </p>
                                <button wire:click="setActiveTab('finance')" class="px-5 py-2 bg-primary-600 hover:bg-primary-500 text-white rounded-xl text-xs font-bold transition-colors">
                                    مشاهده وضعیت تراکنش‌ها و امور مالی
                                </button>
                            </div>
                        @else
                            @if(!$project->handover)
                                <div class="flex flex-col items-center justify-center p-12 text-center space-y-4">
                                    <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-500 border border-blue-200 dark:border-blue-900/50">
                                        <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">در حال آماده‌سازی بسته تحویل</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md leading-relaxed">
                                        تسویه مالی با موفقیت تایید شده است. تیم فنی در حال بارگذاری اطلاعات نهایی دسترسی‌ها و مستندات آموزشی پروژه شما است. به محض تکمیل، محتوا در همین صفحه نمایش داده می‌شود.
                                    </p>
                                </div>
                            @else
                                <!-- Handover content -->
                                <div class="space-y-6">
                                    <!-- Congratulation Section -->
                                    <div class="p-6 bg-gradient-to-r from-primary-50 to-green-50 dark:from-primary-950/20 dark:to-green-950/20 border border-primary-100 dark:border-primary-900/50 rounded-2xl">
                                        <h3 class="text-lg font-black text-primary-700 dark:text-primary-400 flex items-center gap-2">
                                            🎉 تبریک! پروژه شما با موفقیت تحویل شد
                                        </h3>
                                        <div class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed mt-3">
                                            {!! $project->handover->congratulations_message !!}
                                        </div>
                                    </div>

                                    <!-- Training Videos -->
                                    <div class="space-y-3">
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            ویدیوهای آموزشی استفاده از سایت
                                        </h4>
                                        @if(is_array($project->handover->training_videos) && count($project->handover->training_videos) > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($project->handover->training_videos as $video)
                                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-xl flex items-center justify-between gap-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="p-2.5 bg-red-100 dark:bg-red-950/30 text-red-650 rounded-lg">
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                                                            </div>
                                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $video['title'] }}</span>
                                                        </div>
                                                        <a href="{{ $video['url'] }}" target="_blank" class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 text-[11px] font-bold rounded-lg transition-colors flex items-center gap-1 shadow-sm">
                                                            مشاهده ویدیو
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-450 italic py-2">ویدیوی آموزشی برای این پروژه ثبت نشده است.</p>
                                        @endif
                                    </div>

                                    <!-- Final Access Details (Encrypted) -->
                                    @if($project->handover->final_credentials)
                                        <div class="space-y-3">
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                اطلاعات نهایی دسترسی‌ها و مدیریت سایت
                                            </h4>
                                            
                                            <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-250 rounded-xl text-amber-900 dark:text-amber-400 text-xs flex gap-3">
                                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <div>
                                                    <span class="font-bold">نکته بسیار مهم امنیتی:</span>
                                                    <p class="mt-1 opacity-90 leading-relaxed">لطفاً در اولین فرصت اقدام به تغییر رمزهای عبور داده شده نمایید. شرکت هشت هیچ مسئولیتی در قبال نشت اطلاعات بعد از تحویل پروژه نمی‌پذیرد.</p>
                                                </div>
                                            </div>

                                            <div class="relative bg-gray-900 rounded-xl p-4 border border-gray-800 text-left" style="direction: ltr;">
                                                <pre class="font-mono text-xs text-green-450 leading-relaxed overflow-x-auto whitespace-pre-wrap">{{ $project->handover->final_credentials }}</pre>
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

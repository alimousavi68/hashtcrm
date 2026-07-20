<x-filament-panels::page>
    @if($project)
        <div class="space-y-6">
            <!-- Project Header Card -->
            <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">پروژه فعال شما</p>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $project->title }}</h2>
                    </div>
                    <div class="px-4 py-2 bg-primary-50 dark:bg-primary-950/30 text-primary-700 dark:text-primary-400 rounded-full font-medium text-sm border border-primary-200 dark:border-primary-800">
                        {{ $statusLabel }}
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-8">
                    <div class="flex justify-between text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <span>میزان پیشرفت پروژه</span>
                        <span class="text-primary-600 dark:text-primary-400">{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                        <div class="bg-primary-600 h-4 rounded-full transition-all duration-500 ease-out" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
            </div>

            @if($project->status === 'brief')
                <!-- Brief Wizard Form Card -->
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white mb-2">تکمیل بریف پروژه و اطلاعات دسترسی</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        لطفاً مشخصات کسب‌وکار و اطلاعات دسترسی سرور/دامنه خود را با دقت تکمیل کنید تا فرآیند طراحی و توسعه آغاز شود.
                    </p>
                    
                    <form wire:submit.prevent="saveBrief" class="space-y-6">
                        {{ $this->form }}
                    </form>
                </div>
            @endif

            <!-- Timeline Steps Card -->
            <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">مراحل اجرای پروژه</h3>
                
                <div class="relative pl-6 md:pl-8 border-l border-gray-200 dark:border-gray-700 space-y-8 mr-4">
                    @foreach($statuses as $key => $info)
                        @php
                            $isCurrent = $project->status === $key;
                            $isPassed = $progressPercent >= $info['percent'];
                        @endphp
                        <div class="relative">
                            <!-- Bullet Point -->
                            <div class="absolute -left-[33px] md:-left-[41px] top-1.5 flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full border-2 transition-all duration-300
                                {{ $isCurrent ? 'bg-primary-600 border-primary-600 text-white shadow-md' : ($isPassed ? 'bg-green-500 border-green-500 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400') }}">
                                @if($isPassed && !$isCurrent)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <span class="text-xs font-bold">{{ loop->iteration }}</span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="ml-4 md:ml-6">
                                <h4 class="font-bold text-md transition-colors {{ $isCurrent ? 'text-primary-600 dark:text-primary-400' : ($isPassed ? 'text-gray-900 dark:text-white' : 'text-gray-400') }}">
                                    {{ $info['label'] }}
                                </h4>
                                @if($isCurrent)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        پروژه شما هم‌اکنون در این مرحله قرار دارد. اقدامات لازم مربوط به این بخش را در منوی پروژه پیگیری کنید.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contract & Payment Section (visible in contract phase and later) -->
            @if($project->status === 'contract' || $project->contract || $project->payments->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Contract Card -->
                    <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            قرارداد همکاری پروژه
                        </h3>

                        @if($project->contract)
                            @if($project->contract->signed_at)
                                <!-- Signed State -->
                                <div class="p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900/50 rounded-lg text-green-800 dark:text-green-400 space-y-2">
                                    <div class="flex items-center gap-2 font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        این قرارداد با موفقیت امضای دیجیتال شده است
                                    </div>
                                    <p class="text-xs">امضاکننده: {{ $project->contract->signature_name }}</p>
                                    <p class="text-xs">کد ملی: {{ $project->contract->signature_national_code }}</p>
                                    <p class="text-xs">تاریخ امضا: {{ \Illuminate\Support\Carbon::parse($project->contract->signed_at)->format('Y/m/d H:i') }}</p>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg max-h-60 overflow-y-auto text-sm text-gray-700 dark:text-gray-300 prose dark:prose-invert">
                                    {!! $renderedContractContent !!}
                                </div>
                            @else
                                <!-- Unsigned State -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg max-h-60 overflow-y-auto text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 prose dark:prose-invert">
                                    {!! $renderedContractContent !!}
                                </div>
                                
                                <form wire:submit.prevent="signContract" class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">امضای دیجیتال قرارداد</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">نام و نام خانوادگی امضاکننده</label>
                                            <input type="text" wire:model="sigName" placeholder="مثال: علی رضایی" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-950 dark:text-white" required>
                                            @error('sigName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">کد ملی ده رقمی</label>
                                            <input type="text" wire:model="sigNationalCode" maxlength="10" placeholder="مثال: 0012345678" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-950 dark:text-white" required>
                                            @error('sigNationalCode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 rounded-lg text-sm transition-colors">
                                        امضا و پذیرش قرارداد
                                    </button>
                                </form>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">قرارداد همکاری به زودی در این بخش قرار خواهد گرفت.</p>
                        @endif
                    </div>

                    <!-- Payment Card -->
                    <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            امور مالی و آپلود فیش پرداخت
                        </h3>

                        <!-- Bank Information -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg text-sm text-blue-800 dark:text-blue-400">
                            <p class="font-bold mb-1">اطلاعات حساب جهت واریز پیش‌پرداخت / مبالغ فاکتورها:</p>
                            <p class="text-xs mt-1">شماره کارت: ۵۰۲۲-۲۹۱۰-۱۲۳۴-۵۶۷۸</p>
                            <p class="text-xs">به نام: مدیریت شرکت هشت</p>
                            <p class="text-xs mt-1 text-gray-500">پس از واریز، فیش بانکی خود را از فرم زیر ارسال نمایید.</p>
                        </div>

                        <!-- Upload Slip Form -->
                        @if($project->status === 'contract')
                            <form wire:submit.prevent="uploadSlip" class="space-y-4 pt-2">
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">ثبت فیش واریز جدید</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">مبلغ واریزی (تومان)</label>
                                        <input type="number" wire:model="paymentAmount" placeholder="مثال: 5000000" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-950 dark:text-white" required>
                                        @error('paymentAmount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">تصویر فیش واریزی</label>
                                        <input type="file" wire:model="bankSlipFile" class="w-full text-xs text-gray-500 dark:text-gray-400" required>
                                        @error('bankSlipFile') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 rounded-lg text-sm transition-colors">
                                    ارسال و ثبت فیش پرداخت
                                </button>
                            </form>
                        @endif

                        <!-- Payments List -->
                        <div class="space-y-2 pt-4">
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">سوابق پرداخت‌ها</h4>
                            @if($project->payments->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
                                            <tr>
                                                <th class="px-4 py-2 text-right">مبلغ</th>
                                                <th class="px-4 py-2 text-right">وضعیت</th>
                                                <th class="px-4 py-2 text-right">تاریخ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->payments as $payment)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white text-right">
                                                        {{ number_format($payment->amount) }} تومان
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        @if($payment->status === 'approved')
                                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded dark:bg-green-900/30 dark:text-green-400">تایید شده</span>
                                                        @elseif($payment->status === 'rejected')
                                                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded dark:bg-red-900/30 dark:text-red-400">رد شده</span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded dark:bg-yellow-900/30 dark:text-yellow-400">در انتظار بررسی</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-right text-xs">
                                                        {{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('Y/m/d') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400">هیچ پرداختی تاکنون ثبت نشده است.</p>
                            @endif
                        </div>
                    </div>

                </div>
            @endif
        </div>
    @else
        <!-- No Active Project State -->
        <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 text-gray-400 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">هیچ پروژه فعالی یافت نشد</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-md">در حال حاضر هیچ پروژه‌ای برای حساب کاربری شما تعریف نشده است. به محض تعریف پروژه توسط مدیریت، فرآیند آن در این بخش قابل پیگیری خواهد بود.</p>
        </div>
    @endif
</x-filament-panels::page>

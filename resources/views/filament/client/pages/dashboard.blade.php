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

            @if($project->status === 'review')
                <!-- Demo Review Card -->
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                بررسی دمو و پیش‌نمایش پروژه
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">نسخه آزمایشی پروژه خود را بررسی کنید و نظرات خود را برای اصلاح یا تایید نهایی بنویسید.</p>
                        </div>
                    </div>

                    @if(!$project->demo_url)
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-center border border-dashed border-gray-200 dark:border-gray-800">
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">دموی اولیه پروژه در حال آماده‌سازی است. به محض قرار گرفتن لینک دمو توسط تیم فنی، از طریق همین بخش می‌توانید آن را مشاهده و بررسی کنید.</p>
                        </div>
                    @else
                        <!-- Countdown Timer -->
                        @if($project->feedback_deadline)
                            <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <div class="flex items-center gap-2 text-amber-800 dark:text-amber-400">
                                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-bold text-sm">مهلت ارسال بازنگری و فیدبک:</span>
                                </div>
                                <div class="text-sm font-bold text-amber-900 dark:text-amber-300" id="countdown-timer" data-deadline="{{ $project->feedback_deadline->toIso8601String() }}">
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
                                            el.innerText = 'زمان به پایان رسیده است. در حال بروزرسانی صفحه...';
                                            clearInterval(timerInterval);
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 2000);
                                            return;
                                        }
                                        const hours = Math.floor(diff / (1000 * 60 * 60));
                                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                        el.innerText = `${hours} ساعت و ${minutes} دقیقه و ${seconds} ثانیه`;
                                    }
                                    const timerInterval = setInterval(updateTimer, 1000);
                                    updateTimer();
                                })();
                            </script>
                        @endif

                        <!-- Demo URL Button -->
                        <div class="flex justify-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
                            <a href="{{ $project->demo_url }}" target="_blank" class="fi-btn fi-btn-color-primary bg-primary-600 hover:bg-primary-500 text-white shadow px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                مشاهده دمو و پیش‌نمایش زنده سایت
                            </a>
                        </div>

                        <!-- Feedback Form -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">ثبت نظر یا اعلام مغایرت‌ها:</h4>
                            <textarea wire:model="feedbackNotes" placeholder="در صورتی که بخش‌های نیاز به اصلاح وجود دارد، جزئیات آن را در این قسمت به صورت گام به گام وارد نمایید..." rows="4" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-950 dark:text-white focus:ring-primary-500 focus:border-primary-500"></textarea>
                            @error('feedbackNotes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button wire:click="submitFeedback('needs_changes')" class="flex-1 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/30 text-red-700 dark:text-red-400 font-bold py-2.5 rounded-xl text-sm border border-red-200 dark:border-red-900/50 transition-colors">
                                    ثبت نیاز به اصلاحات و تغییرات
                                </button>
                                <button wire:click="submitFeedback('approved')" class="flex-1 bg-green-600 hover:bg-green-500 text-white font-bold py-2.5 rounded-xl text-sm shadow transition-colors">
                                    تایید نهایی دمو و ارسال به مرحله بعد
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

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

            <!-- Ticketing & Support Section -->
            <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 space-y-6 mt-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    پشتیبانی و تیکت‌های پروژه
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Tickets List (Right Panel) -->
                    <div class="space-y-4 lg:col-span-1 border-r lg:border-l border-gray-100 dark:border-gray-700 pr-0 lg:pl-6">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">لیست تیکت‌ها</h4>

                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            @forelse($tickets as $t)
                                <button wire:click="selectTicket({{ $t->id }})" class="w-full text-right p-3 rounded-lg border text-sm transition-colors duration-150 flex flex-col gap-1.5
                                    {{ $activeTicketId === $t->id 
                                        ? 'bg-primary-50 dark:bg-primary-950/20 border-primary-300 dark:border-primary-800' 
                                        : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-750 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $t->subject }}</span>
                                        @if($t->status === 'open')
                                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-850 rounded dark:bg-red-950/30 dark:text-red-400">باز</span>
                                        @elseif($t->status === 'replied')
                                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-805 rounded dark:bg-green-950/30 dark:text-green-400">پاسخ داده شده</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-gray-200 text-gray-800 rounded dark:bg-gray-700 dark:text-gray-400">بسته شده</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Carbon::parse($t->created_at)->format('Y/m/d H:i') }}</span>
                                </button>
                            @empty
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">تیکتی ثبت نشده است.</p>
                            @endforelse
                        </div>

                        <!-- New Ticket Toggle -->
                        <div class="pt-4 border-t border-gray-150 dark:border-gray-700">
                            <h5 class="font-bold text-xs text-gray-905 dark:text-white mb-3">ثبت تیکت جدید</h5>
                            <form wire:submit.prevent="createTicket" class="space-y-3">
                                <input type="text" wire:model="newTicketSubject" placeholder="موضوع تیکت" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                <textarea wire:model="newTicketMessage" placeholder="پیام شما..." rows="3" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required></textarea>
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 rounded-lg text-xs transition-colors">
                                    ثبت و ارسال تیکت
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Ticket Conversation (Left Panel) -->
                    <div class="lg:col-span-2 flex flex-col min-h-[300px]">
                        @if($activeTicketId)
                            @php
                                $selectedTicket = collect($tickets)->firstWhere('id', $activeTicketId);
                            @endphp
                            @if($selectedTicket)
                                <div class="flex-1 flex flex-col justify-between space-y-4">
                                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">گفتگو: {{ $selectedTicket->subject }}</h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">وضعیت: 
                                            @if($selectedTicket->status === 'open')
                                                <span class="text-red-600 dark:text-red-400 font-medium">در انتظار پاسخ</span>
                                            @elseif($selectedTicket->status === 'replied')
                                                <span class="text-green-600 dark:text-green-400 font-medium">پاسخ داده شده</span>
                                            @else
                                                <span class="text-gray-500 font-medium">بسته شده</span>
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Chat Messages Box -->
                                    <div class="flex-1 space-y-3 max-h-80 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-150 dark:border-gray-800">
                                        @foreach($selectedTicket->messages as $msg)
                                            @php
                                                $isMe = $msg->sender_id === Auth::id();
                                            @endphp
                                            <div class="flex flex-col {{ $isMe ? 'items-start' : 'items-end' }}">
                                                <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-xs shadow-sm
                                                    {{ $isMe 
                                                        ? 'bg-primary-600 text-white rounded-tr-none' 
                                                        : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-tl-none' }}">
                                                    <div class="font-bold text-[10px] mb-1 opacity-90 flex items-center gap-1">
                                                        <span>{{ $msg->sender->name }}</span>
                                                        <span class="text-[9px] opacity-75">({{ $msg->sender->role === 'admin' ? 'پشتیبان' : 'مشتری' }})</span>
                                                    </div>
                                                    <p class="whitespace-pre-line leading-relaxed">{{ $msg->message }}</p>
                                                    <span class="text-[9px] mt-1 block text-left opacity-75">
                                                        {{ \Illuminate\Support\Carbon::parse($msg->created_at)->format('Y/m/d H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Chat Input -->
                                    @if($selectedTicket->status !== 'closed')
                                        <form wire:submit.prevent="sendChatMessage" class="flex gap-2">
                                            <input type="text" wire:model="newChatMessage" placeholder="پیام خود را بنویسید..." class="flex-1 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-4 py-2 rounded-lg text-xs transition-colors">
                                                ارسال
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-900/20 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400">یک تیکت را برای مشاهده گفتگو انتخاب کنید یا تیکت جدیدی ایجاد کنید.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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

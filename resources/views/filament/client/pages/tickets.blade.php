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
        .chat-bubble {
            transition: transform 0.15s ease-out;
        }
    </style>

    <div class="space-y-6 vazir-font">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm space-y-6">
            <h3 class="text-md font-bold text-gray-900 dark:text-white flex items-center gap-2 border-b pb-3 dark:border-gray-700">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                پشتیبانی و تیکت‌های گفتگو
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Tickets List (Right Panel) -->
                <div class="space-y-4 lg:col-span-1 border-r lg:border-l border-gray-100 dark:border-gray-700 pr-0 lg:pl-6">
                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">لیست تیکت‌ها</h4>

                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @forelse($tickets as $t)
                            <button wire:click="selectTicket({{ $t->id }})" class="w-full text-right p-3 rounded-lg border text-xs transition-colors duration-150 flex flex-col gap-1.5
                                {{ $activeTicketId === $t->id 
                                    ? 'bg-primary-50 dark:bg-primary-950/20 border-primary-300 dark:border-primary-800' 
                                    : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-750 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <div class="flex items-center justify-between w-full">
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $t->subject }}</span>
                                    @if($t->status === 'open')
                                        <span class="px-2 py-0.5 text-[9px] font-semibold bg-red-100 text-red-850 rounded dark:bg-red-950/30 dark:text-red-400">باز</span>
                                    @elseif($t->status === 'replied')
                                        <span class="px-2 py-0.5 text-[9px] font-semibold bg-green-100 text-green-805 rounded dark:bg-green-950/30 dark:text-green-400">پاسخ داده شده</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[9px] font-semibold bg-gray-200 text-gray-800 rounded dark:bg-gray-700 dark:text-gray-400">بسته شده</span>
                                    @endif
                                </div>
                                <div class="flex justify-between w-full text-[9px] text-gray-450 mt-1">
                                    <span>پروژه: {{ $t->project->title }}</span>
                                    <span>{{ \App\Helpers\JalaliHelper::toJalali($t->created_at, 'Y/m/d H:i') }}</span>
                                </div>
                            </button>
                        @empty
                            <p class="text-xs text-gray-500 dark:text-gray-450 text-center py-4">تیکتی ثبت نشده است.</p>
                        @endforelse
                    </div>

                    <!-- New Ticket creation -->
                    <div class="pt-4 border-t border-gray-150 dark:border-gray-700">
                        <h5 class="font-bold text-xs text-gray-905 dark:text-white mb-3">ثبت تیکت جدید مستقیم</h5>
                        <form wire:submit.prevent="createTicket" class="space-y-3">
                            <div>
                                <label class="block text-[10px] text-gray-500 mb-1">انتخاب پروژه مربوطه</label>
                                <select wire:model="newTicketProjectId" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                    <option value="">انتخاب کنید...</option>
                                    @foreach($myProjects as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                                @error('newTicketProjectId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 mb-1">موضوع تیکت</label>
                                <input type="text" wire:model="newTicketSubject" placeholder="موضوع تیکت خود را بنویسید" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
                                @error('newTicketSubject') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 mb-1">متن پیام پشتیبانی</label>
                                <textarea wire:model="newTicketMessage" placeholder="جزئیات درخواست یا مشکل خود را بنویسید..." rows="3" class="custom-input w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required></textarea>
                                @error('newTicketMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm">
                                ارسال تیکت پشتیبانی
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
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">گفتگو: {{ $selectedTicket->subject }}</h4>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">وضعیت: 
                                        @if($selectedTicket->status === 'open')
                                            <span class="text-red-650 font-medium">در انتظار پاسخ</span>
                                        @elseif($selectedTicket->status === 'replied')
                                            <span class="text-green-600 font-medium">پاسخ داده شده</span>
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
                                            <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-xs shadow-sm chat-bubble
                                                {{ $isMe 
                                                    ? 'bg-primary-600 text-white rounded-tr-none' 
                                                    : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-tl-none' }}">
                                                <div class="font-bold text-[10px] mb-1 opacity-90 flex items-center gap-1">
                                                    <span>{{ $msg->sender->name }}</span>
                                                    <span class="text-[9px] opacity-75">({{ $msg->sender->role === 'admin' ? 'پشتیبان' : 'مشتری' }})</span>
                                                </div>
                                                <p class="whitespace-pre-line leading-relaxed">{{ $msg->message }}</p>
                                                <span class="text-[9px] mt-1 block text-left opacity-75">
                                                    {{ \App\Helpers\JalaliHelper::toJalali($msg->created_at, 'Y/m/d H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Chat Input -->
                                @if($selectedTicket->status !== 'closed')
                                    <form wire:submit.prevent="sendChatMessage" class="flex gap-2">
                                        <input type="text" wire:model="newChatMessage" placeholder="پیام خود را بنویسید..." class="custom-input flex-1 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-xs text-gray-950 dark:text-white" required>
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
                            <p class="text-xs text-gray-500 dark:text-gray-450">یک تیکت را برای مشاهده گفتگو انتخاب کنید یا از پنل سمت راست تیکت جدیدی برای پروژه انتخابی خود ثبت نمایید.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<div class="flex flex-col gap-3 max-h-[450px] overflow-y-auto p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
    @if($ticket && $ticket->messages->count() > 0)
        @foreach($ticket->messages as $message)
            @php
                $isAdmin = $message->sender && $message->sender->role === 'admin';
            @endphp
            <div class="flex flex-col {{ $isAdmin ? 'items-start' : 'items-end' }} w-full">
                <div class="max-w-[80%] rounded-2xl p-3.5 text-sm shadow-sm transition-all duration-200
                    {{ $isAdmin 
                        ? 'bg-indigo-600 dark:bg-indigo-500 text-white rounded-tr-none' 
                        : 'bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-700 rounded-tl-none' }}">
                    <div class="font-bold text-xs mb-1.5 opacity-95 flex items-center gap-1.5 border-b border-white/20 dark:border-slate-700/50 pb-1">
                        <span>{{ $message->sender?->name ?? 'کاربر' }}</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded {{ $isAdmin ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                            {{ $isAdmin ? 'پشتیبان' : 'مشتری' }}
                        </span>
                    </div>
                    <p class="whitespace-pre-line leading-relaxed text-xs sm:text-sm my-1">{{ $message->message }}</p>
                    <span class="text-[10px] block text-left opacity-75 mt-1 pt-1 font-mono">
                        {{ \App\Helpers\JalaliHelper::toJalali($message->created_at, 'Y/m/d H:i') }}
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <div class="flex flex-col items-center justify-center text-center py-8 text-slate-400 dark:text-slate-500 gap-2">
            <svg class="w-8 h-8 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <p class="text-xs">هنوز گفتگویی در این تیکت ثبت نشده است.</p>
        </div>
    @endif
</div>

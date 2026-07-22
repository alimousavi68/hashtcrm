<div class="space-y-4 max-h-96 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
    @if($ticket && $ticket->messages->count() > 0)
        @foreach($ticket->messages as $message)
            @php
                $isAdmin = $message->sender->role === 'admin';
            @endphp
            <div class="flex flex-col {{ $isAdmin ? 'items-start' : 'items-end' }}">
                <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-sm shadow-sm
                    {{ $isAdmin 
                        ? 'bg-primary-600 text-white rounded-tr-none' 
                        : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-tl-none' }}">
                    <div class="font-bold text-xs mb-1 opacity-90 flex items-center gap-1">
                        <span>{{ $message->sender->name }}</span>
                        <span class="text-[10px] opacity-75">({{ $isAdmin ? 'پشتیبان' : 'مشتری' }})</span>
                    </div>
                    <div class="whitespace-pre-line leading-relaxed">{{ $message->message }}</div>
                    <div class="text-[10px] mt-1 text-left opacity-75">
                        {{ \App\Helpers\JalaliHelper::toJalali($message->created_at, 'Y/m/d H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">گفتگویی ثبت نشده است.</p>
    @endif
</div>

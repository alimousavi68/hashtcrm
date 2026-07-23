<div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-1">
    {{-- Card 1: Balance --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50/60 via-white to-white dark:from-emerald-950/30 dark:via-gray-900 dark:to-gray-900 border border-emerald-200/80 dark:border-emerald-800/50 rounded-2xl p-5 shadow-sm transition-all duration-200 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold tracking-wide text-emerald-800 dark:text-emerald-300">موجودی اعتبار درگاه</span>
                @if($balanceData)
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ number_format($balanceData['credit'] ?? 0) }}
                        <span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">{{ $balanceData['currency'] ?? 'ریال' }}</span>
                    </div>
                @else
                    <div class="text-sm font-bold text-amber-600 dark:text-amber-400">نامشخص / نیازمند تست اتصال</div>
                @endif
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <x-heroicon-o-credit-card style="width: 1.5rem; height: 1.5rem;" />
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-emerald-100 dark:border-emerald-900/40 flex items-center text-xs text-emerald-700 dark:text-emerald-400 font-medium">
            <x-heroicon-o-check-circle style="width: 1rem; height: 1rem; margin-left: 0.35rem;" />
            <span>استعلام آنلاین از سرور IPPanel</span>
        </div>
    </div>

    {{-- Card 2: Active Driver --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50/60 via-white to-white dark:from-blue-950/30 dark:via-gray-900 dark:to-gray-900 border border-blue-200/80 dark:border-blue-800/50 rounded-2xl p-5 shadow-sm transition-all duration-200 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold tracking-wide text-blue-800 dark:text-blue-300">درگاه فعال فعلی</span>
                <div class="text-2xl font-black text-blue-600 dark:text-blue-400">
                    {{ strtoupper($driver ?? 'ippanel') }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                <x-heroicon-o-server-stack style="width: 1.5rem; height: 1.5rem;" />
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-blue-100 dark:border-blue-900/40 flex items-center text-xs text-blue-700 dark:text-blue-400 font-medium">
            <x-heroicon-o-signal style="width: 1rem; height: 1rem; margin-left: 0.35rem;" />
            <span>درگاه اصلی ارسال انبوه و پترن</span>
        </div>
    </div>

    {{-- Card 3: Sandbox Mode --}}
    <div class="relative overflow-hidden bg-gradient-to-br {{ ($sandbox ?? false) ? 'from-amber-50/60 via-white to-white dark:from-amber-950/30' : 'from-indigo-50/60 via-white to-white dark:from-indigo-950/30' }} dark:via-gray-900 dark:to-gray-900 border {{ ($sandbox ?? false) ? 'border-amber-200/80 dark:border-amber-800/50' : 'border-indigo-200/80 dark:border-indigo-800/50' }} rounded-2xl p-5 shadow-sm transition-all duration-200 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold tracking-wide text-gray-700 dark:text-gray-300">حالت تست (Sandbox)</span>
                <div class="text-lg font-black {{ ($sandbox ?? false) ? 'text-amber-600 dark:text-amber-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                    {{ ($sandbox ?? false) ? 'فعال (شبیه‌ساز)' : 'غیرفعال (ارسال واقعی)' }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl {{ ($sandbox ?? false) ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' }} flex items-center justify-center shrink-0">
                <x-heroicon-o-beaker style="width: 1.5rem; height: 1.5rem;" />
            </div>
        </div>
        <div class="mt-3 pt-3 border-t {{ ($sandbox ?? false) ? 'border-amber-100 dark:border-amber-900/40 text-amber-700 dark:text-amber-400' : 'border-indigo-100 dark:border-indigo-900/40 text-indigo-700 dark:text-indigo-400' }} flex items-center text-xs font-medium">
            <x-heroicon-o-information-circle style="width: 1rem; height: 1rem; margin-left: 0.35rem;" />
            <span>{{ ($sandbox ?? false) ? 'پیامک‌ها فقط لاگ می‌شوند' : 'پیامک‌ها به کاربر تحویل داده می‌شود' }}</span>
        </div>
    </div>
</div>

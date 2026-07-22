<div class="inline-flex items-center gap-3 bg-gray-50 dark:bg-gray-800/80 px-3.5 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 shadow-2xs text-xs font-semibold text-gray-700 dark:text-gray-200">
    <span class="flex items-center gap-1.5">
        <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
        <span>پیشرفت بریف: {{ $filledFields }} از {{ $totalFields }} پاسخ</span>
    </span>
    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
        <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" style="width: {{ $completionPercent }}%"></div>
    </div>
    <span class="font-mono font-bold text-primary-600 dark:text-primary-400 text-xs">{{ $completionPercent }}%</span>
</div>

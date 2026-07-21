@php
    $record = $getRecord();
    if (!$record) return;

    $percent = $record->getProgressPercent();
    $label = $record->getStatusLabel();
    
    // Choose status badge color classes
    $statusClasses = match($record->status) {
        'draft' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700',
        'brief' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border-amber-200 dark:border-amber-800',
        'contract' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300 border-sky-200 dark:border-sky-800',
        'in_progress' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
        'review' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border-rose-200 dark:border-rose-800',
        'ready_handover' => 'bg-teal-50 text-teal-700 dark:bg-teal-950/50 dark:text-teal-300 border-teal-200 dark:border-teal-800',
        'completed' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
        default => 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700',
    };

    $progressColor = match($record->status) {
        'completed' => 'bg-emerald-500',
        'ready_handover' => 'bg-teal-500',
        'review' => 'bg-rose-500',
        'in_progress' => 'bg-indigo-600',
        'contract' => 'bg-sky-500',
        'brief' => 'bg-amber-500',
        default => 'bg-slate-500',
    };
@endphp

<div class="py-1 space-y-2 min-w-[210px] max-w-[280px]">
    <!-- Top Row: Badge & Percent -->
    <div class="flex items-center justify-between gap-2">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses }}">
            <span class="h-1.5 w-1.5 rounded-full bg-current {{ $record->status !== 'completed' ? 'animate-pulse' : '' }}"></span>
            {{ $label }}
        </span>
        <span class="text-xs font-mono font-bold text-gray-700 dark:text-gray-300">
            {{ $percent }}%
        </span>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
        <div class="h-full {{ $progressColor }} rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
    </div>
</div>



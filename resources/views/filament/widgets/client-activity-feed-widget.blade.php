@php
    $activities = $this->getActivities();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-x-2">
                <x-heroicon-o-bolt class="w-5 h-5 text-amber-500" />
                <span class="font-bold text-base">آخرین اکشن‌ها و اعلانات مشتریان</span>
            </div>
        </x-slot>

        <div class="flow-root mt-2">
            <ul role="list" class="-mb-6 space-y-4">
                @if(count($activities) > 0)
                    @foreach($activities as $activity)
                        <li class="relative flex gap-x-4 pb-4 border-b border-gray-100 dark:border-gray-800 last:border-none last:pb-0">
                            <div class="relative flex h-8 w-8 flex-none items-center justify-center rounded-full {{ $activity['color'] }} text-white shadow-sm">
                                <x-dynamic-component :component="$activity['icon']" class="h-4 w-4" />
                            </div>
                            <div class="flex-auto">
                                <div class="flex justify-between items-center gap-x-4">
                                    <h4 class="text-xs font-bold text-gray-900 dark:text-gray-100">
                                        {{ $activity['title'] }}
                                    </h4>
                                    <time class="flex-none text-[11px] text-gray-400 dark:text-gray-500">
                                        {{ isset($activity['created_at']) && $activity['created_at'] ? $activity['created_at']->diffForHumans() : '' }}
                                    </time>
                                </div>
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 line-clamp-1">
                                    {{ $activity['description'] }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="py-6 text-center text-xs text-gray-500">
                        هیچ اکشن یا رویداد جدیدی یافت نشد.
                    </li>
                @endif
            </ul>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

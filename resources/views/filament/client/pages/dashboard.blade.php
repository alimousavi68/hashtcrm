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

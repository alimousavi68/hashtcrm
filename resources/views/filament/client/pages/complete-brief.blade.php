<x-filament-panels::page>
    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">تکمیل فرم بریف</h1>
            <p class="text-gray-500 dark:text-gray-400">لطفاً اطلاعات زیر را برای شروع پروژه تکمیل کنید. می‌توانید بخش‌های غیراجباری را بعداً تکمیل کنید.</p>
        </div>

        <form wire:submit="submitForm">
            {{ $this->form }}
        </form>
    </div>
</x-filament-panels::page>

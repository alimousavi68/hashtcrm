<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'میز کار مشتری';
    protected static string $view = 'filament.client.pages.dashboard';

    public ?Project $project = null;
    public int $progressPercent = 0;
    public string $statusLabel = '';

    public array $statuses = [
        'draft' => ['label' => 'پیش‌نویس اولیه', 'percent' => 10],
        'brief' => ['label' => 'تکمیل بریف نیازمندی‌ها', 'percent' => 25],
        'contract' => ['label' => 'امضای قرارداد و امور مالی', 'percent' => 45],
        'in_progress' => ['label' => 'در حال طراحی و توسعه', 'percent' => 65],
        'review' => ['label' => 'بازنگری و ثبت نظرات (دمو)', 'percent' => 80],
        'ready_handover' => ['label' => 'آماده‌سازی بسته تحویل', 'percent' => 90],
        'completed' => ['label' => 'تحویل نهایی و خاتمه پروژه', 'percent' => 100],
    ];

    public function mount(): void
    {
        // Get the latest project for the logged-in client
        $this->project = Project::where('client_id', Auth::id())
            ->latest()
            ->first();

        if ($this->project) {
            $statusKey = $this->project->status;
            $statusInfo = $this->statuses[$statusKey] ?? ['label' => 'نامشخص', 'percent' => 0];
            $this->progressPercent = $statusInfo['percent'];
            $this->statusLabel = $statusInfo['label'];
        }
    }
}

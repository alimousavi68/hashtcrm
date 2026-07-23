<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'status',
        'is_settled',
        'brief_schema',
        'demo_url',
        'feedback_deadline',
        'reminder_count',
        'last_reminded_at',
    ];

    protected $casts = [
        'is_settled' => 'boolean',
        'brief_schema' => 'array',
        'feedback_deadline' => 'datetime',
        'last_reminded_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::updating(function ($project) {
            if ($project->isDirty('status')) {
                $project->reminder_count = 0;
                $project->last_reminded_at = null;
            }
        });
    }

    public static array $statuses = [
        'draft' => ['label' => 'پیش‌نویس / سرنخ جدید', 'percent' => 5],
        'brief' => ['label' => 'تکمیل بریف نیازمندی‌ها', 'percent' => 15],
        'proforma' => ['label' => 'صدور پیش‌فاکتور', 'percent' => 25],
        'contract' => ['label' => 'امضای قرارداد و پیش‌پرداخت', 'percent' => 35],
        'ui_design' => ['label' => 'طراحی رابط کاربری (UI)', 'percent' => 50],
        'development' => ['label' => 'توسعه و برنامه‌نویسی', 'percent' => 70],
        'review' => ['label' => 'بازنگری و دمو نهایی', 'percent' => 85],
        'ready_handover' => ['label' => 'آماده‌سازی بسته تحویل', 'percent' => 95],
        'completed' => ['label' => 'تحویل نهایی و خاتمه پروژه', 'percent' => 100],
        'rejected' => ['label' => 'رد شده توسط ادمین', 'percent' => 0],
        'cancelled' => ['label' => 'انصراف مشتری', 'percent' => 0],
    ];

    public function getProgressPercent(): int
    {
        return self::$statuses[$this->status]['percent'] ?? 0;
    }

    public function getStatusLabel(): string
    {
        return self::$statuses[$this->status]['label'] ?? 'نامشخص';
    }

    public function getStagesSplit(): array
    {
        $keys = array_keys(self::$statuses);
        $currentIndex = array_search($this->status, $keys);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }
        
        $completed = [];
        $remaining = [];
        $current = null;

        foreach (self::$statuses as $key => $info) {
            $index = array_search($key, $keys);
            if ($index < $currentIndex) {
                $completed[] = $info['label'];
            } elseif ($index === $currentIndex) {
                $current = $info['label'];
            } else {
                $remaining[] = $info['label'];
            }
        }

        return [
            'completed' => $completed,
            'current' => $current,
            'remaining' => $remaining,
        ];
    }

    protected function casts(): array
    {
        return [
            'feedback_deadline' => 'datetime',
            'is_settled' => 'boolean',
            'brief_schema' => 'array',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function briefAnswer()
    {
        return $this->hasOne(BriefAnswer::class);
    }

    public function credential()
    {
        return $this->hasOne(ProjectCredential::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function proforma()
    {
        return $this->hasOne(Proforma::class);
    }

    public function handover()
    {
        return $this->hasOne(Handover::class);
    }
}

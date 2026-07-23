<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\NoLayout;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

#[NoLayout]
class StartOnboarding extends \Livewire\Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public bool $otpSent = false;

    public function mount()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'client') {
                return redirect()->to(url('/client'));
            }
            return redirect()->to(url('/admin'));
        }
        
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('نام و نام خانوادگی')
                    ->required()
                    ->disabled(fn () => $this->otpSent),
                TextInput::make('phone')
                    ->label('شماره موبایل')
                    ->required()
                    ->tel()
                    ->disabled(fn () => $this->otpSent)
                    ->placeholder('۰۹۱۲۳۴۵۶۷۸۹')
                    ->extraInputAttributes(['style' => 'direction: ltr; text-align: left;']),
                TextInput::make('otp')
                    ->label('کد تایید (OTP)')
                    ->required(fn () => $this->otpSent)
                    ->visible(fn () => $this->otpSent)
                    ->placeholder('12345')
                    ->extraInputAttributes(['style' => 'direction: ltr; text-align: center; font-weight: bold; letter-spacing: 0.5em;']),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $phone = $data['phone'];

        if (!$this->otpSent) {
            // Find or create User
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                $user = User::create([
                    'phone' => $phone,
                    'name' => $data['name'],
                    'role' => 'client',
                ]);
            }

            // Generate OTP
            $otpCode = rand(10000, 99999);
            $user->otp_code = $otpCode;
            $user->otp_expires_at = Carbon::now()->addMinutes(5);
            $user->save();

            // Simulate SMS
            logger()->info("OTP Code for user {$phone} is: {$otpCode}");

            Notification::make()
                ->title('کد تایید ارسال شد')
                ->body("کد تایید: {$otpCode}")
                ->success()
                ->send();

            $this->otpSent = true;
            return;
        }

        // Verify OTP
        $user = User::where('phone', $phone)->first();
        if (!$user || $user->otp_code !== $data['otp'] || Carbon::parse($user->otp_expires_at)->isPast()) {
            Notification::make()
                ->title('خطا')
                ->body('کد وارد شده نامعتبر یا منقضی شده است.')
                ->danger()
                ->send();
            return;
        }

        // Reset OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Login user
        Auth::login($user);
        session()->regenerate();

        // Check if they already have a draft project
        $project = Project::where('client_id', $user->id)->where('status', 'draft')->first();
        
        if (!$project) {
            // Find default or first active BriefTemplate
            $template = \App\Models\BriefTemplate::where('is_default', true)->first() 
                ?? \App\Models\BriefTemplate::where('is_active', true)->first();

            // Create Draft Project
            $project = Project::create([
                'client_id' => $user->id,
                'title' => 'پروژه جدید - ' . $user->name,
                'status' => 'draft',
                'brief_schema' => $template?->schema ?? [],
            ]);
        }

        // Redirect to Client Panel
        return redirect()->to(url('/client'));
    }

    public function render()
    {
        return view('livewire.start-onboarding');
    }
}

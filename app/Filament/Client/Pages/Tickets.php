<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class Tickets extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $title = 'پشتیبانی و تیکت‌ها';
    protected string $view = 'filament.client.pages.tickets';

    public $tickets = [];
    public $myProjects = [];
    
    public ?int $activeTicketId = null;
    
    // New Ticket fields
    public ?string $newTicketSubject = '';
    public ?int $newTicketProjectId = null;
    public ?string $newTicketMessage = '';
    
    // Chat field
    public ?string $newChatMessage = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $clientId = Auth::guard('client')->id();
        
        $this->myProjects = Project::where('client_id', $clientId)->latest()->get();
        
        $this->tickets = Ticket::where('client_id', $clientId)
            ->with(['messages.sender', 'project'])
            ->latest()
            ->get();
            
        if ($this->activeTicketId) {
            // Refresh active ticket conversation
            $activeTicketExists = collect($this->tickets)->contains('id', $this->activeTicketId);
            if (!$activeTicketExists) {
                $this->activeTicketId = null;
            }
        }
    }

    public function selectTicket(int $ticketId): void
    {
        $this->activeTicketId = $ticketId;
        $this->loadData();
    }

    public function createTicket(): void
    {
        $this->validate([
            'newTicketSubject' => 'required|string|min:3',
            'newTicketProjectId' => 'required|exists:projects,id',
            'newTicketMessage' => 'required|string|min:5',
        ], [
            'newTicketSubject.required' => 'موضوع تیکت الزامی است.',
            'newTicketSubject.min' => 'موضوع باید حداقل ۳ کاراکتر باشد.',
            'newTicketProjectId.required' => 'انتخاب پروژه الزامی است.',
            'newTicketProjectId.exists' => 'پروژه انتخاب شده معتبر نیست.',
            'newTicketMessage.required' => 'متن پیام الزامی است.',
            'newTicketMessage.min' => 'پیام باید حداقل ۵ کاراکتر باشد.',
        ]);

        $ticket = Ticket::create([
            'project_id' => $this->newTicketProjectId,
            'client_id' => Auth::guard('client')->id(),
            'subject' => $this->newTicketSubject,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'sender_id' => Auth::guard('client')->id(),
            'message' => $this->newTicketMessage,
        ]);

        // Send admin notification
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProjectNotification(
                $ticket->project,
                'تیکت جدید از مشتری',
                "مشتری برای پروژه «{$ticket->project->title}» تیکت جدیدی با موضوع «{$ticket->subject}» ثبت کرد.",
                'tickets'
            ));
        }

        $this->newTicketSubject = '';
        $this->newTicketProjectId = null;
        $this->newTicketMessage = '';
        $this->activeTicketId = $ticket->id;

        Notification::make()
            ->title('تیکت پشتیبانی ثبت شد')
            ->body('تیکت شما با موفقیت ارسال شد و به زودی توسط پشتیبان بررسی می‌شود.')
            ->success()
            ->send();

        $this->loadData();
    }

    public function sendChatMessage(): void
    {
        $this->validate([
            'newChatMessage' => 'required|string|min:1',
        ], [
            'newChatMessage.required' => 'نمی‌توانید پیام خالی ارسال کنید.',
        ]);

        $ticket = Ticket::where('client_id', Auth::guard('client')->id())->find($this->activeTicketId);
        if (!$ticket) return;

        $ticket->messages()->create([
            'sender_id' => Auth::guard('client')->id(),
            'message' => $this->newChatMessage,
        ]);

        $ticket->update(['status' => 'open']);

        // Send admin notification
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProjectNotification(
                $ticket->project,
                'پیام جدید در تیکت پشتیبانی',
                "مشتری پیام جدیدی برای تیکت «{$ticket->subject}» ارسال کرد.",
                'tickets'
            ));
        }

        $this->newChatMessage = '';
        $this->loadData();
    }
}

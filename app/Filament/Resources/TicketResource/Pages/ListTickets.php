<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
    protected string $view = 'filament.resources.ticket-resource.pages.list-tickets';

    public ?int $activeTicketId = null;
    public ?string $newChatMessage = '';
    public string $filterStatus = 'all'; // all, unread, open, replied, closed
    public bool $showTableMode = false;

    public function mount(): void
    {
        parent::mount();
        $latestTicket = Ticket::where('is_read_by_admin', false)->latest('updated_at')->first() 
            ?? Ticket::latest('updated_at')->first();
            
        if ($latestTicket) {
            $this->selectTicket($latestTicket->id);
        }
    }

    public function selectTicket(int $ticketId): void
    {
        $this->activeTicketId = $ticketId;
        $ticket = Ticket::find($ticketId);
        if ($ticket && !$ticket->is_read_by_admin) {
            $ticket->update(['is_read_by_admin' => true]);
        }
    }

    public function sendChatMessage(): void
    {
        $this->validate([
            'newChatMessage' => 'required|string|min:1',
        ], [
            'newChatMessage.required' => 'متن پیام الزامی است.',
        ]);

        $ticket = Ticket::find($this->activeTicketId);
        if (!$ticket) return;

        $ticket->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $this->newChatMessage,
        ]);

        $ticket->update([
            'status' => 'replied',
            'is_read_by_admin' => true,
            'is_read_by_client' => false,
            'updated_at' => now(),
        ]);

        if ($ticket->client) {
            $ticket->client->notify(new \App\Notifications\ProjectNotification(
                $ticket->project,
                'پاسخ جدید به تیکت پشتیبانی',
                "پشتیبان به تیکت «{$ticket->subject}» پاسخ داد.",
                'tickets'
            ));
        }

        $this->newChatMessage = '';

        Notification::make()
            ->title('پاسخ با موفقیت ارسال شد')
            ->success()
            ->send();
    }

    public function updateTicketStatus(string $status): void
    {
        $ticket = Ticket::find($this->activeTicketId);
        if ($ticket) {
            $ticket->update([
                'status' => $status,
                'updated_at' => now(),
            ]);
            Notification::make()->title('وضعیت تیکت بروزرسانی شد')->success()->send();
        }
    }

    public function toggleTableMode(): void
    {
        $this->showTableMode = !$this->showTableMode;
    }

    public function getViewData(): array
    {
        $query = Ticket::with(['client', 'project', 'messages.sender'])->orderBy('updated_at', 'desc');

        if ($this->filterStatus === 'unread') {
            $query->where('is_read_by_admin', false);
        } elseif ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $tickets = $query->get();
        $unreadCount = Ticket::where('is_read_by_admin', false)->count();

        return array_merge(parent::getViewData(), [
            'adminTickets' => $tickets,
            'unreadTicketsCount' => $unreadCount,
            'selectedTicket' => $this->activeTicketId ? Ticket::with(['messages.sender', 'client', 'project'])->find($this->activeTicketId) : null,
        ]);
    }
}

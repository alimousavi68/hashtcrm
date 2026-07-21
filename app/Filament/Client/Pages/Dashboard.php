<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'داشبورد';
    protected string $view = 'filament.client.pages.dashboard';

    public int $totalProjects = 0;
    public int $activeProjects = 0;
    public int $completedProjects = 0;
    public int $openTickets = 0;

    public function mount(): void
    {
        $clientId = Auth::guard('client')->id();

        $this->totalProjects = Project::where('client_id', $clientId)->count();
        $this->activeProjects = Project::where('client_id', $clientId)->where('status', '!=', 'completed')->count();
        $this->completedProjects = Project::where('client_id', $clientId)->where('status', 'completed')->count();
        $this->openTickets = Ticket::where('client_id', $clientId)->where('status', 'open')->count();
    }
}

<x-filament-panels::page>
    <style>
        .hasht-admin-tickets-container { display: flex; flex-direction: column; gap: 16px; font-family: 'PeydaWebVF', sans-serif !important; direction: rtl; }
        .hasht-admin-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 8px; }
        .hasht-admin-sec-title { font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .dark .hasht-admin-sec-title { color: #f8fafc; }

        .hasht-admin-tickets-layout { display: grid; grid-template-columns: 360px 1fr; gap: 18px; }
        @media (max-width: 960px) {
            .hasht-admin-tickets-layout { grid-template-columns: 1fr; }
        }

        .hasht-admin-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .dark .hasht-admin-card { background: #0f172a; border-color: #1e293b; }

        .hasht-filter-btn { padding: 5px 10px; font-size: 11px; font-weight: 700; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px; }
        .dark .hasht-filter-btn { background: #1e293b; border-color: #334155; color: #94a3b8; }
        .hasht-filter-btn-active { background: #4f46e5 !important; border-color: #4f46e5 !important; color: #ffffff !important; }
        
        .hasht-admin-ticket-item { width: 100%; text-align: right; padding: 12px 14px; border-radius: 10px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 12px; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 6px; position: relative; }
        .dark .hasht-admin-ticket-item { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        .hasht-admin-ticket-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .dark .hasht-admin-ticket-item:hover { background: #334155; }
        .hasht-admin-ticket-item-active { background: #eef2ff !important; border-color: #a5b4fc !important; }
        .dark .hasht-admin-ticket-item-active { background: #312e81 !important; border-color: #6366f1 !important; }

        .hasht-unread-badge { background: #ef4444; color: #ffffff; font-size: 10px; font-weight: 800; padding: 1px 6px; border-radius: 10px; }
        .hasht-unread-pulse { width: 8px; height: 8px; border-radius: 50%; background: #ef4444; display: inline-block; box-shadow: 0 0 0 2px rgba(239,68,68,0.3); }

        .hasht-badge-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .hasht-status-open { background: #fef2f2; color: #991b1b; }
        .dark .hasht-status-open { background: #450a0a; color: #fca5a5; }
        .hasht-status-replied { background: #f0fdf4; color: #166534; }
        .dark .hasht-status-replied { background: #052e16; color: #86efac; }
        .hasht-status-closed { background: #f1f5f9; color: #475569; }
        .dark .hasht-status-closed { background: #334155; color: #cbd5e1; }

        .hasht-admin-chat-messages { display: flex; flex-direction: column; gap: 12px; max-height: 420px; overflow-y: auto; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; }
        .dark .hasht-admin-chat-messages { background: #020617; border-color: #1e293b; }

        .hasht-bubble-admin { background: #4f46e5; color: #ffffff; border-top-right-radius: 2px; align-self: flex-start; max-width: 82%; border-radius: 12px; padding: 10px 14px; font-size: 13px; line-height: 1.6; }
        .hasht-bubble-client { background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; border-top-left-radius: 2px; align-self: flex-end; max-width: 82%; border-radius: 12px; padding: 10px 14px; font-size: 13px; line-height: 1.6; }
        .dark .hasht-bubble-client { background: #1e293b; color: #f8fafc; border-color: #334155; }

        .custom-chat-input { width: 100%; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; transition: border-color 0.2s; color: #0f172a; }
        .dark .custom-chat-input { background: #1e293b; border-color: #334155; color: #f8fafc; }
        .custom-chat-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }

        .btn-action-sm { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s; }
        .dark .btn-action-sm { background: #1e293b; border-color: #334155; color: #cbd5e1; }
        .btn-action-sm:hover { background: #f1f5f9; color: #0f172a; }
    </style>

    <div class="hasht-admin-tickets-container">
        <!-- Top Toolbar -->
        <div class="hasht-admin-sec-heading">
            <div class="hasht-admin-sec-title">
                <svg style="width: 22px; height: 22px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>مرکز پاسخگویی و پشتیبانی آنلاین</span>
                @if($unreadTicketsCount > 0)
                    <span class="hasht-unread-badge">{{ $unreadTicketsCount }} خوانده نشده</span>
                @endif
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <button wire:click="toggleTableMode" class="btn-action-sm" style="display: flex; align-items: center; gap: 6px;">
                    @if($showTableMode)
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span>نمای چت تعاملی</span>
                    @else
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>نمای جدول لاراول</span>
                    @endif
                </button>
            </div>
        </div>

        @if($showTableMode)
            <!-- Standard Filament Table Mode -->
            <div class="hasht-admin-card" style="padding: 0; overflow: hidden;">
                {{ $this->table }}
            </div>
        @else
            <!-- Interactive 2-Column Chat Mode -->
            <div class="hasht-admin-tickets-layout">
                <!-- Sidebar: Filters & Ticket List -->
                <div class="hasht-admin-card" style="display: flex; flex-direction: column; gap: 14px;">
                    <!-- Filter Tabs -->
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <button wire:click="$set('filterStatus', 'all')" class="hasht-filter-btn {{ $filterStatus === 'all' ? 'hasht-filter-btn-active' : '' }}">
                            همه
                        </button>
                        <button wire:click="$set('filterStatus', 'unread')" class="hasht-filter-btn {{ $filterStatus === 'unread' ? 'hasht-filter-btn-active' : '' }}">
                            خوانده نشده
                            @if($unreadTicketsCount > 0)
                                <span style="background: #ef4444; color: #fff; padding: 0 4px; border-radius: 6px; font-size: 9px;">{{ $unreadTicketsCount }}</span>
                            @endif
                        </button>
                        <button wire:click="$set('filterStatus', 'open')" class="hasht-filter-btn {{ $filterStatus === 'open' ? 'hasht-filter-btn-active' : '' }}">
                            باز
                        </button>
                        <button wire:click="$set('filterStatus', 'replied')" class="hasht-filter-btn {{ $filterStatus === 'replied' ? 'hasht-filter-btn-active' : '' }}">
                            پاسخ داده شد
                        </button>
                    </div>

                    <!-- Tickets List -->
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 480px; overflow-y: auto;">
                        @forelse($adminTickets as $t)
                            @php
                                $isUnread = !$t->is_read_by_admin;
                            @endphp
                            <button wire:click="selectTicket({{ $t->id }})" class="hasht-admin-ticket-item {{ $activeTicketId === $t->id ? 'hasht-admin-ticket-item-active' : '' }}" style="{{ $isUnread ? 'border-color: #fca5a5;' : '' }}">
                                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        @if($isUnread)
                                            <span class="hasht-unread-pulse"></span>
                                        @endif
                                        <span style="font-size: 13px; font-weight: {{ $isUnread ? '900' : '700' }}; color: {{ $isUnread ? '#ef4444' : 'inherit' }};">
                                            {{ $t->subject }}
                                        </span>
                                    </div>
                                    @if($t->status === 'open')
                                        <span class="hasht-badge-status hasht-status-open">جدید</span>
                                    @elseif($t->status === 'replied')
                                        <span class="hasht-badge-status hasht-status-replied">پاسخ داده شده</span>
                                    @else
                                        <span class="hasht-badge-status hasht-status-closed">بسته</span>
                                    @endif
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 11px; opacity: 0.8; margin-top: 2px;">
                                    <span>مشتری: <strong>{{ $t->client->name }}</strong></span>
                                    <span>{{ \App\Helpers\JalaliHelper::toJalali($t->updated_at ?? $t->created_at, 'Y/m/d H:i') }}</span>
                                </div>
                                <div style="font-size: 10px; opacity: 0.65;">
                                    پروژه: {{ $t->project->title }}
                                </div>
                            </button>
                        @empty
                            <p style="font-size: 12px; text-align: center; padding: 24px; color: #94a3b8;">تیکتی در این فیلتر یافت نشد.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Main Panel: Live Chat & Reply Form -->
                <div class="hasht-admin-card" style="display: flex; flex-direction: column; justify-content: space-between; gap: 14px; min-height: 480px;">
                    @if($selectedTicket)
                        <!-- Header Chat -->
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;" class="dark:border-slate-800">
                            <div>
                                <h3 style="font-size: 15px; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <span>{{ $selectedTicket->subject }}</span>
                                    <span style="font-size: 11px; font-weight: 500; opacity: 0.7;">(پروژه: {{ $selectedTicket->project->title }})</span>
                                </h3>
                                <span style="font-size: 12px; opacity: 0.8;">مشتری: <strong>{{ $selectedTicket->client->name }}</strong> ({{ $selectedTicket->client->mobile ?? 'بدون شماره' }})</span>
                            </div>

                            <!-- Action Status Buttons -->
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <button wire:click="updateTicketStatus('open')" class="btn-action-sm {{ $selectedTicket->status === 'open' ? 'style="background:#fee2e2; color:#991b1b;"' : '' }}">
                                    در انتظار پاسخ
                                </button>
                                <button wire:click="updateTicketStatus('replied')" class="btn-action-sm {{ $selectedTicket->status === 'replied' ? 'style="background:#dcfce7; color:#166534;"' : '' }}">
                                    پاسخ داده شده
                                </button>
                                <button wire:click="updateTicketStatus('closed')" class="btn-action-sm {{ $selectedTicket->status === 'closed' ? 'style="background:#f1f5f9; color:#475569;"' : '' }}">
                                    بستن تیکت
                                </button>
                            </div>
                        </div>

                        <!-- Chat Messages Container -->
                        <div class="hasht-admin-chat-messages">
                            @foreach($selectedTicket->messages as $msg)
                                @php
                                    $isAdminMsg = $msg->sender && $msg->sender->role === 'admin';
                                @endphp
                                <div class="hasht-bubble-{{ $isAdminMsg ? 'admin' : 'client' }}">
                                    <div style="font-size: 11px; font-weight: 800; margin-bottom: 4px; opacity: 0.95; display: flex; align-items: center; gap: 4px;">
                                        <span>{{ $msg->sender?->name ?? 'کاربر' }}</span>
                                        <span style="opacity: 0.8; font-size: 10px;">({{ $isAdminMsg ? 'پشتیبان' : 'مشتری' }})</span>
                                    </div>
                                    <p style="margin: 0; white-space: pre-line;">{{ $msg->message }}</p>
                                    <span style="font-size: 10px; opacity: 0.8; display: block; text-align: left; margin-top: 6px;">
                                        {{ \App\Helpers\JalaliHelper::toJalali($msg->created_at, 'Y/m/d H:i') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Live Reply Form -->
                        <form wire:submit.prevent="sendChatMessage" style="display: flex; gap: 10px; align-items: center; margin-top: 4px;">
                            <input type="text" wire:model="newChatMessage" placeholder="پاسخ خود را بنویسید و کلید اینتر را بزنید..." class="custom-chat-input" style="flex-grow: 1;" required>
                            <button type="submit" style="padding: 10px 20px; font-size: 13px; font-weight: 700; background: #4f46e5; color: #ffffff; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>ارسال پاسخ</span>
                            </button>
                        </form>
                    @else
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px; color: #94a3b8; gap: 12px; height: 100%;">
                            <svg style="width: 42px; height: 42px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p style="font-size: 13px; margin: 0; max-width: 320px;">جهت مشاهده گفتگو و ارسال پاسخ آنلاین، یک تیکت را از لیست سمت راست انتخاب کنید.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        .hasht-client-container { display: flex; flex-direction: column; gap: 20px; font-family: 'Vazirmatn', sans-serif !important; direction: rtl; }
        .hasht-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .hasht-sec-title { font-size: 14px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }

        .hasht-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.25s ease; }

        .hasht-tickets-layout { display: grid; grid-template-columns: 320px 1fr; gap: 16px; }
        @media (max-width: 900px) {
            .hasht-tickets-layout { grid-template-columns: 1fr; }
        }

        .hasht-ticket-item { width: 100%; text-align: right; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 11px; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 4px; }
        .hasht-ticket-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .hasht-ticket-item-active { background: #eef2ff !important; border-color: #c7d2fe !important; }

        .hasht-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; white-space: nowrap; }
        .hasht-badge-open { background: #fef2f2; color: #991b1b; }
        .hasht-badge-replied { background: #f0fdf4; color: #166534; }
        .hasht-badge-closed { background: #f1f5f9; color: #475569; }

        .hasht-chat-container { display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 400px; gap: 12px; }
        .hasht-chat-messages { display: flex; flex-direction: column; gap: 10px; max-height: 380px; overflow-y: auto; padding: 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; }

        .hasht-bubble { max-width: 80%; border-radius: 12px; padding: 10px 14px; font-size: 11px; line-height: 1.6; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .hasht-bubble-me { background: #4f46e5; color: #ffffff; border-top-right-radius: 2px; align-self: flex-start; }
        .hasht-bubble-other { background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; border-top-left-radius: 2px; align-self: flex-end; }

        .hasht-manage-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 700; color: #ffffff !important; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #4f46e5; border: none; transition: all 0.2s; font-size: 11px; cursor: pointer; }
        .hasht-manage-btn:hover { background: #4338ca; }

        .custom-input { width: 100%; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 11px; outline: none; transition: border-color 0.2s; }
        .custom-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }

        svg { flex-shrink: 0; }
    </style>

    <div class="hasht-client-container">
        <div class="hasht-card">
            <div class="hasht-sec-heading">
                <div class="hasht-sec-title">
                    <svg style="width: 18px; height: 18px; color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>پشتیبانی و تیکت‌های گفتگو</span>
                </div>
            </div>

            <div class="hasht-tickets-layout">
                <!-- Sidebar: Ticket List & New Ticket Form -->
                <div style="display: flex; flex-direction: column; gap: 16px; border-left: 1px solid #e2e8f0; padding-left: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">لیست تیکت‌ها</h4>
                        <div style="display: flex; flex-direction: column; gap: 6px; max-height: 240px; overflow-y: auto;">
                            @forelse($tickets as $t)
                                <button wire:click="selectTicket({{ $t->id }})" class="hasht-ticket-item {{ $activeTicketId === $t->id ? 'hasht-ticket-item-active' : '' }}">
                                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                        <span style="font-weight: 700; color: #0f172a;">{{ $t->subject }}</span>
                                        @if($t->status === 'open')
                                            <span class="hasht-badge hasht-badge-open">در انتظار پاسخ</span>
                                        @elseif($t->status === 'replied')
                                            <span class="hasht-badge hasht-badge-replied">پاسخ داده شده</span>
                                        @else
                                            <span class="hasht-badge hasht-badge-closed">بسته شده</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-size: 10px; color: #64748b; margin-top: 2px;">
                                        <span>پروژه: {{ $t->project->title }}</span>
                                        <span>{{ \App\Helpers\JalaliHelper::toJalali($t->created_at, 'Y/m/d H:i') }}</span>
                                    </div>
                                </button>
                            @empty
                                <p style="font-size: 11px; color: #64748b; text-align: center; padding: 12px; background: #f8fafc; border-radius: 8px;">تیکتی ثبت نشده است.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- New Ticket Form -->
                    <div style="padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 10px;">
                        <h5 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 0;">ثبت تیکت جدید</h5>
                        <form wire:submit.prevent="createTicket" style="display: flex; flex-direction: column; gap: 10px;">
                            <div>
                                <label style="display: block; font-size: 10px; color: #64748b; margin-bottom: 3px;">انتخاب پروژه مربوطه</label>
                                <select wire:model="newTicketProjectId" class="custom-input" required>
                                    <option value="">انتخاب کنید...</option>
                                    @foreach($myProjects as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                                @error('newTicketProjectId') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 10px; color: #64748b; margin-bottom: 3px;">موضوع تیکت</label>
                                <input type="text" wire:model="newTicketSubject" placeholder="موضوع تیکت خود را بنویسید" class="custom-input" required>
                                @error('newTicketSubject') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 10px; color: #64748b; margin-bottom: 3px;">متن پیام پشتیبانی</label>
                                <textarea wire:model="newTicketMessage" placeholder="جزئیات درخواست یا مشکل خود را بنویسید..." rows="3" class="custom-input" required></textarea>
                                @error('newTicketMessage') <span style="font-size: 10px; color: #ef4444;">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="hasht-manage-btn" style="width: 100%;">
                                ارسال تیکت پشتیبانی
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Panel: Ticket Conversation -->
                <div class="hasht-chat-container">
                    @if($activeTicketId)
                        @php
                            $selectedTicket = collect($tickets)->firstWhere('id', $activeTicketId);
                        @endphp
                        @if($selectedTicket)
                            <div style="display: flex; flex-direction: column; gap: 12px; height: 100%; justify-content: space-between;">
                                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                                    <h4 style="font-size: 12px; font-weight: 800; color: #0f172a; margin: 0;">گفتگو: {{ $selectedTicket->subject }}</h4>
                                    <span style="font-size: 11px; color: #64748b;">وضعیت: 
                                        @if($selectedTicket->status === 'open')
                                            <span style="color: #ef4444; font-weight: 700;">در انتظار پاسخ</span>
                                        @elseif($selectedTicket->status === 'replied')
                                            <span style="color: #16a34a; font-weight: 700;">پاسخ داده شده</span>
                                        @else
                                            <span style="color: #64748b; font-weight: 700;">بسته شده</span>
                                        @endif
                                    </span>
                                </div>

                                <!-- Chat Messages List -->
                                <div class="hasht-chat-messages">
                                    @foreach($selectedTicket->messages as $msg)
                                        @php
                                            $isMe = $msg->sender_id === Auth::id();
                                        @endphp
                                        <div class="hasht-bubble {{ $isMe ? 'hasht-bubble-me' : 'hasht-bubble-other' }}">
                                            <div style="font-size: 10px; font-weight: 700; margin-bottom: 4px; opacity: 0.9; display: flex; align-items: center; gap: 4px;">
                                                <span>{{ $msg->sender->name }}</span>
                                                <span style="opacity: 0.75;">({{ $msg->sender->role === 'admin' ? 'پشتیبان' : 'شما' }})</span>
                                            </div>
                                            <p style="margin: 0; white-space: pre-line;">{{ $msg->message }}</p>
                                            <span style="font-size: 9px; opacity: 0.75; display: block; text-align: left; margin-top: 4px;">
                                                {{ \App\Helpers\JalaliHelper::toJalali($msg->created_at, 'Y/m/d H:i') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Chat Message Input -->
                                @if($selectedTicket->status !== 'closed')
                                    <form wire:submit.prevent="sendChatMessage" style="display: flex; gap: 8px;">
                                        <input type="text" wire:model="newChatMessage" placeholder="پیام خود را بنویسید..." class="custom-input" style="flex-grow: 1;" required>
                                        <button type="submit" class="hasht-manage-btn">
                                            ارسال
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @else
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 48px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; gap: 8px; height: 100%;">
                            <svg style="width: 32px; height: 32px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p style="font-size: 11px; color: #64748b; margin: 0; max-width: 320px;">یک تیکت را برای مشاهده گفتگو انتخاب کنید یا از پنل سمت راست تیکت جدیدی برای پروژه انتخابی خود ثبت نمایید.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

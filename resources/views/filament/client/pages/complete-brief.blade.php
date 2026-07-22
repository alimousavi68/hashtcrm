<x-filament-panels::page>
    <style>
        .hasht-client-container { display: flex; flex-direction: column; gap: 20px; font-family: 'PeydaWebVF', sans-serif !important; direction: rtl; }
        .hasht-sec-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        
        .hasht-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .hasht-card-header { background: linear-gradient(135deg, #eef2ff 0%, #ffffff 100%); border: 1px solid #c7d2fe; border-radius: 12px; padding: 22px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }

        .hasht-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; white-space: nowrap; }

        .hasht-manage-btn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #334155; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #f1f5f9; border: 1px solid #cbd5e1; transition: all 0.2s; font-size: 13px; cursor: pointer; }
        .hasht-manage-btn:hover { background: #e2e8f0; color: #0f172a; }

        svg { flex-shrink: 0; }
    </style>

    <div class="hasht-client-container">
        <!-- Header Card -->
        <div class="hasht-card-header">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="hasht-badge">پروسه آنبوردینگ</span>
                    @if($project)
                        <span style="font-size: 13px; color: #64748b;">پروژه: <strong style="color: #0f172a; font-size: 14px;">{{ $project->title }}</strong></span>
                    @endif
                </div>
                <h2 style="font-size: 20px; font-weight: 900; color: #0f172a; margin-top: 8px;">تکمیل پرسشنامه و بریف نیازمندی‌ها</h2>
                <p style="font-size: 13px; color: #475569; margin-top: 4px; line-height: 1.6;">لطفاً مشخصات کلیدی کسب‌وکار، دامنه، نیازمندی‌ها و اطلاعات اولیه پروژه خود را گام‌به‌گام وارد نمایید.</p>
            </div>

            <a href="{{ route('filament.client.pages.projects') }}" class="hasht-manage-btn">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                <span>بازگشت به میزکار پروژه</span>
            </a>
        </div>

        <!-- Wizard Form Container -->
        <div class="hasht-card">
            <form wire:submit="submitForm">
                {{ $this->form }}
            </form>
        </div>
    </div>
</x-filament-panels::page>

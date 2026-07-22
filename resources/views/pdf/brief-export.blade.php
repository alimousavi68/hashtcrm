<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>خروجی پرسشنامه پروژه {{ $project->title }}</title>
    <style>
        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Vazirmatn', 'Tahoma', 'Arial', sans-serif !important;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 30px;
            font-size: 13px;
            line-height: 1.7;
            direction: rtl;
            text-align: right;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            font-size: 20px;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .header-title p {
            font-size: 12px;
            color: #64748b;
        }

        .meta-badge {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 15px;
            text-align: left;
        }

        .meta-badge strong {
            color: #4f46e5;
            font-size: 14px;
        }

        .step-card {
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .step-header {
            background: #f1f5f9;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }

        .step-body {
            padding: 20px;
        }

        .qa-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px stroke #f1f5f9;
        }

        .qa-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .question-label {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .answer-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            color: #334155;
            font-size: 13px;
            white-space: pre-line;
        }

        table.group-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.group-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            font-size: 12px;
        }

        table.group-table td.sub-title {
            background: #f1f5f9;
            font-weight: bold;
            width: 30%;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    @if(!empty($isPdfMode))
        <div class="no-print" style="max-width: 850px; margin: 0 auto 15px auto; text-align: left;">
            <button onclick="window.print()" style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                🖨️ پرینت یا ذخیره به صورت PDF
            </button>
        </div>
    @endif

    <div class="container">
        <!-- هدر سند -->
        <div class="header">
            <div class="header-title">
                <h1>سند نیازمندی‌ها و بریف پروژه: {{ $project->title }}</h1>
                <p>کارفرما: {{ $project->client ? $project->client->name : 'نامشخص' }} | تاریخ ثبت: {{ $createdJalali }}</p>
            </div>
            <div class="meta-badge">
                <div>درصد تکمیل بریف: <strong>٪{{ $completionPercent }}</strong></div>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">پاسخ داده‌شده: {{ $filledFields }} از {{ $totalFields }} سوال</div>
            </div>
        </div>

        <!-- گام‌ها و پاسخ‌ها -->
        @foreach($categorizedAnswers as $stepTitle => $items)
            <div class="step-card">
                <div class="step-header">
                    📌 {{ $stepTitle }}
                </div>
                <div class="step-body">
                    @foreach($items as $qa)
                        <div class="qa-item">
                            <div class="question-label">{{ $qa['label'] }}</div>

                            @if(is_array($qa['value']))
                                @if($qa['type'] === 'input_group')
                                    <table class="group-table">
                                        @foreach($qa['value'] as $subKey => $subVal)
                                            <tr>
                                                <td class="sub-title">{{ $subKey }}</td>
                                                <td>{{ $subVal }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @elseif($qa['type'] === 'repeater')
                                    <div class="answer-box">
                                        @if(empty($qa['value']))
                                            —
                                        @else
                                            <ul style="padding-right: 20px;">
                                                @foreach($qa['value'] as $repVal)
                                                    <li>{{ $repVal }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="answer-box">
                                    {{ !empty($qa['value']) ? $qa['value'] : '—' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="footer">
            این سند به صورت خودکار توسط سامانه مدیریت پروژه‌های هشت ایجاد گردیده است.
        </div>
    </div>

    @if(!empty($isPdfMode))
        <script>
            // پرینت خودکار هنگام باز شدن صفحه در مرورگر
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        </script>
    @endif
</body>
</html>

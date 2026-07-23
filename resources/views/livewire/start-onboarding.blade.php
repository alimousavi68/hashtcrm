<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شروع پروژه جدید | هشت بهشت</title>
    @filamentStyles
    <style>
        body { font-family: 'PeydaWebVF', tahoma, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4">
    <div style="width: 100%; max-width: 440px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; padding: 32px; margin: 40px auto; box-sizing: border-box;">
        <div style="text-align: center; margin-bottom: 24px;">
            <h1 style="font-size: 24px; font-weight: 900; color: #4f46e5; margin: 0 0 8px 0;">هشت بهشت</h1>
            <p style="font-size: 13px; color: #64748b; margin: 0;">برای شروع ثبت درخواست، مشخصات خود را وارد کنید</p>
        </div>

        <form wire:submit="submit">
            {{ $this->form }}

            <button type="submit" style="background-color: #4f46e5; color: #ffffff; padding: 12px 16px; border-radius: 10px; width: 100%; font-weight: 700; font-size: 14px; margin-top: 24px; cursor: pointer; border: none; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25); transition: all 0.2s;">
                {{ $otpSent ? 'تایید کد و ورود' : 'دریافت کد تایید' }}
            </button>
        </form>
    </div>

    @filamentScripts
</body>
</html>

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class MagicLinkController extends Controller
{
    public function verify(Request $request, $token)
    {
        $user = User::where('magic_link_token', $token)
            ->where('magic_link_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect('/client/login')->with('error', 'لینک جادویی نامعتبر یا منقضی شده است.');
        }

        // Clean token
        $user->magic_link_token = null;
        $user->magic_link_expires_at = null;
        $user->save();

        // Login user
        if ($user->role === 'admin') {
            Auth::guard('web')->login($user);
            $request->session()->regenerate();
            return redirect('/admin');
        }

        Auth::guard('client')->login($user);
        $request->session()->regenerate();

        $redirectUrl = '/client';
        if ($request->has('redirect_to')) {
            $path = $request->query('redirect_to');
            // Basic security check to ensure it's a relative path starting with /client/
            if (str_starts_with($path, '/client/')) {
                $redirectUrl = $path;
            }
        }

        return redirect($redirectUrl);
    }
}

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
        Auth::login($user);

        $request->session()->regenerate();

        // Redirect to their respective panel
        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/client');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return renderView('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|string|email|max:255']);
        $email = strtolower(trim($request->input('email')));
        $user = User::whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();
        if ($user) {
            try {
                $status = Password::sendResetLink(['email' => $user->email]);
                if ($status === Password::RESET_THROTTLED) {
                    return back()->withErrors(['email' => 'A reset link was requested recently. Please wait one minute before requesting another.'])->withInput($request->only('email'));
                }
            } catch (\Throwable $exception) {
                Password::deleteToken($user);
                return back()->withErrors(['email' => 'Password reset email is currently unavailable. Please contact site support.'])->withInput($request->only('email'));
            }
        }
        flash('success', 'If this email is registered, a password reset link has been sent. Check your inbox and spam folder.');
        return back();
    }

    public function resetForm(Request $request, string $token)
    {
        $request->validate(['email' => 'required|string|email|max:255']);
        return renderView('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'token' => 'required|string', 'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $credentials['password_confirmation'] = $request->input('password_confirmation');
        $status = Password::reset($credentials, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });
        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => 'This reset link is invalid or expired. Please request a new link.'])->withInput($request->only('email'));
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        \Illuminate\Support\Facades\RateLimiter::clear('login:'.hash('sha256', strtolower(trim($request->input('email'))).'|'.$request->ip()));
        flash('success', 'Password updated. Sign in with your new password to open your dashboard.');
        return redirect('/login')->withInput($request->only('email'));
    }
}

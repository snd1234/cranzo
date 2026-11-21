<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Notifier;
use App\Services\ShopCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class CustomerAuthController extends BaseController
{
    public function showLogin()
    {
        return view('auth.customer_login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $guestSession = $request->session()->getId();

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those details do not match an account.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $this->claimGuestOrders($guestSession, (int) Auth::id());
        ShopCart::sanitize();
        \App\Http\Controllers\WishlistController::claim($guestSession, (int) Auth::id());

        if (! $request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended(route('account.dashboard'));
    }

    public function showRegister()
    {
        return view('auth.customer_register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/', 'unique:users,mobile_number'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
            'terms' => ['accepted'],
        ]);

        $guestSession = $request->session()->getId();
        $user = User::create([
            'username' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $sent = app(Notifier::class)->userCreated($user);
        $request->session()->regenerate();
        $this->claimGuestOrders($guestSession, (int) $user->id);
        ShopCart::sanitize();
        \App\Http\Controllers\WishlistController::claim($guestSession, (int) Auth::id());

        return redirect()->route('verification.notice')->with(
            $sent ? 'success' : 'error',
            $sent
                ? 'Your account is created. Confirm the email we sent before using the account.'
                : 'Your account is created, but the confirmation email could not be sent. Use the resend button.'
        );
    }

    public function verifyNotice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.customer_verify');
    }

    public function sendVerification(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('account.dashboard');
        }

        $sent = app(Notifier::class)->userCreated($user);

        return back()->with(
            $sent ? 'success' : 'error',
            $sent ? 'A fresh confirmation email has been sent.' : 'The confirmation email could not be sent. Try again shortly.'
        );
    }

    public function verifyEmail(Request $request, int $id, string $hash)
    {
        $user = $request->user();
        abort_unless($user && (int) $user->id === $id, 403);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('account.dashboard')->with('success', 'Your email is confirmed.');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showForgot()
    {
        return view('auth.customer_forgot');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        Password::sendResetLink($request->only('email'));

        return back()->with('success', 'If that email has an account, a reset link has been sent.');
    }

    public function showReset(string $token)
    {
        return view('auth.customer_reset', ['token' => $token, 'email' => request('email')]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Your password has been reset. Please sign in.')
            : back()->withErrors(['email' => 'This reset link is invalid or has expired.']);
    }

    private function claimGuestOrders(string $guestSession, int $userId): void
    {
        if ($guestSession === '') {
            return;
        }

        DB::table('orders')
            ->where('user_id', 0)
            ->where('session_id', $guestSession)
            ->update(['user_id' => $userId]);
    }
}

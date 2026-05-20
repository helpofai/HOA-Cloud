<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginAuthLivewireComponent extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('email', 'Too many login attempts. Please try again in '.RateLimiter::availableIn($throttleKey).' seconds.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();

            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($throttleKey);
        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('app.Modules.Auth.Views.login-auth-livewire-component')
            ->layout('layouts.app', ['title' => 'Login - Hoa Cloud']);
    }
}

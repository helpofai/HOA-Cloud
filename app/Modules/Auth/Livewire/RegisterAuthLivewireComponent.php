<?php

namespace App\Modules\Auth\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterAuthLivewireComponent extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('app.Modules.Auth.Views.register-auth-livewire-component')
            ->layout('layouts.app', ['title' => 'Create Account - Hoa Cloud']);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public function logout()
    {
        auth()->guard()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
    public function render()
    {
        return view('livewire.navigation');
    }
}

<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    // Binding Variable
    #[Rule('required')]
    public $username;
    #[Rule('required')]
    public $password;

    public function mount() {
        $this->username = '';
        $this->password = '';
    }
    
    public function render()
    {
        return view('livewire.components.login');
    }

    public function login() {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->dispatch('refresh-page');
        } else {
            session()->flash('auth-invalid', 'username atau password salah');
        }
        

    }

}

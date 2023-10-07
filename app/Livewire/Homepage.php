<?php

namespace App\Livewire;

use App\Models\Coral;
use Livewire\Component;

class Homepage extends Component
{
    public $storedCorals;

    public function mount() {
        $this->storedCorals = Coral::get()->toArray();
    }

    public function render()
    {
        return view('livewire.homepage');
    }
}

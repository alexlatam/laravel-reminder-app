<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Contracts\Support\Renderable;

class Spinner extends Component
{
    public function render(): Renderable
    {
        return view('livewire.spinner');
    }
}

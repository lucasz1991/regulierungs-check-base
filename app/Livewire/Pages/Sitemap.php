<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Sitemap extends Component
{
    public function render()
    {
        return view('livewire.pages.sitemap')->layout('layouts.app');
    }
}

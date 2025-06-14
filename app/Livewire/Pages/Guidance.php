<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Setting;

class Guidance extends Component
{
    public $status;
    public $assistantName;

    public function mount()
    {
        $this->status = Setting::getValue('ai_assistant', 'status');
        $this->assistantName = Setting::getValue('ai_assistant', 'assistant_name');
    }

    public function render()
    {
        return view('livewire.pages.guidance')->layout('layouts.app');
    }
}

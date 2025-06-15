<?php

namespace App\Livewire\Admin\Tools\Tests;

use Livewire\Component;
use App\Models\Setting;

class StreamChatTest extends Component
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
        return view('livewire.admin.tools.tests.stream-chat-test')->layout('layouts.app');
    }
}

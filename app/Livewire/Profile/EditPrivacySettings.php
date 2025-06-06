<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditPrivacySettings extends Component
{
    public $privacy = [];

    public function mount()
    {
        $this->privacy = Auth::user()->privacy_settings ?? [
            'comments' => [
                'name_visibility' => 'users',
                'avatar_visibility' => 'users',
            ],
            'ratings' => [
                'name_visibility' => 'users',
                'avatar_visibility' => 'users',
            ],
        ];
    }

    public function save()
    {
        Auth::user()->update([
            'privacy_settings' => $this->privacy,
        ]);
        $this->dispatch('saved');
        session()->flash('message', 'Customer information updated successfully!');
    }

    public function render()
    {
        return view('livewire.profile.edit-privacy-settings');
    }
}

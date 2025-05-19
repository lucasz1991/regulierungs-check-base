<?php

namespace App\Livewire\Testphase;

use Livewire\Component;

class ModalNotice extends Component
{
    public bool $show = true;

    public function hide()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.testphase.modal-notice');
    }
}

<?php

namespace App\Livewire\Tools;

use Livewire\Component;
use App\Models\Insurance;

class SearchModal extends Component
{
    public $show = false;
    public $query = '';
    public $resultsInsurances = [];

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $this->resultsInsurances = Insurance::where('name', 'like', '%' . $this->query . '%')
        ->limit(10)
        ->get();
    
    }

    public function selectResult($id)
    {
        $this->dispatch('search-result-selected', id: $id);
        $this->reset(['query', 'results', 'show']);
    }

    public function render()
    {
        return view('livewire.tools.search-modal');
    }
}


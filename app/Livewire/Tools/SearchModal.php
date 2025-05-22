<?php

namespace App\Livewire\Tools;

use Livewire\Component;
use App\Models\Insurance;

class SearchModal extends Component
{
    public $show = false;
    public $query = '';
    public $searchType = 'insurance';

    public $resultsInsurances = [];


    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->resultsInsurances = [];
            return;
        }

        switch ($this->searchType) {
            case 'insurance_type':
                $this->resultsInsurances = InsuranceType::where('name', 'like', '%' . $this->query . '%')
                    ->get()->toArray();
                break;
            case 'content':
                $this->resultsInsurances = Insurance::where('description', 'like', '%' . $this->query . '%')
                    ->get()->toArray();
                break;
            default:
                $this->resultsInsurances = Insurance::where('name', 'like', '%' . $this->query . '%')
                    ->limit(10)
                    ->get();
                break;
        }
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


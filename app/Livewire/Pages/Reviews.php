<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClaimRating; // Beispielmodell für Bewertungen
use App\Models\Insurance;   // Beispielmodell für Versicherungen
use App\Models\InsuranceSubtype; // Beispielmodell für Versicherungstypen

class Reviews extends Component
{
    use WithPagination;

    public $insuranceSubTypes = [];
    public $selectedInsuranceSubTypefilter = [];

    public $insurances = [];
    public $selectedInsurancesfilter = [];

    public $perPage = 20;
    public $pages = 1;
    public $sort;
    public $search;
    public $minAvgScore;

    public function mount()
    {
        $this->insuranceSubTypes = InsuranceSubtype::has('claimRatings')->get();
        $this->insurances = Insurance::has('claimRatings')->get();
        $this->search = '';
        $this->pages = 1;
        $this->sort = 'score_desc';
    }

    public function loadMore()
    {
        $this->pages++;
    }

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function updatingSort()
    {
        $this->resetPage();
    }
    public function updatingMinAvgScore()
    {
        $this->resetPage();
    }

    public function updatingSelectedInsuranceSubTypefilter()
    {
        $this->resetPage();
    }

    public function updatingSelectedInsurancesfilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'selectedInsuranceSubTypefilter',
            'search',
            'minAvgScore',
        ]);
    }

    public function render()
    {
        $query = ClaimRating::with(['insurance', 'insuranceSubtype'])
            ->whereNotNull('rating_score')->where('status', 'rated')->where('is_public', true);

        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->whereHas('insurance', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });

                $query->orWhere('moderator_comment', 'like', '%' . $this->search . '%');

                // Freitext aus attachments (z. B. ai_overall_comment)
                $query->orWhere('attachments->scorings->ai_overall_comment', 'like', '%' . $this->search . '%');

                // Freitextantwort in answers → regulationDetail.textarea_value
                $query->orWhere('answers->regulationDetail->textarea_value', 'like', '%' . $this->search . '%');

                // Weitere JSON-Felder (optional, wenn sinnvoll)
                $query->orWhere('answers->Service-Kommentar', 'like', '%' . $this->search . '%');
            });
        }

        // Filter: Versicherungs-Subtyp
        if (!empty($this->selectedInsuranceSubTypefilter)) {
            $query->whereIn('insurance_subtype_id', $this->selectedInsuranceSubTypefilter);
        }

        // Filter: Versicherungen
        if (!empty($this->selectedInsurancesfilter)) {
            $query->whereIn('insurance_id', $this->selectedInsurancesfilter);
        }

        // Filter: Mindestdurchschnittsnote
        if (isset($this->minAvgScore) && is_numeric($this->minAvgScore)) {
            $query->where('rating_score', '>=', $this->minAvgScore);
        }
        // Sortierung
        switch ($this->sort) {
            case 'score_asc':
                $query->orderBy('rating_score', 'asc');
                break;
            case 'score_desc':
                $query->orderBy('rating_score', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }


        // Pagination (Load More)
        $claimRatings = $query->paginate($this->perPage * $this->pages);

        return view('livewire.pages.reviews', [
            'claimRatings' => $claimRatings,
        ])->layout('layouts.app');
    }

}

<?php

namespace App\Livewire\Customer\Rating;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\InsuranceSubtype;
use App\Models\InsuranceType;
use App\Models\Insurance;
use App\Models\RatingQuestion;
use App\Models\RatingQuestionnaireVersion;
use App\Models\ClaimRating;

class RatingForm extends Component
{
    public $insuranceTypeId = null;
    public $insuranceType;
    public $insuranceSubTypeId = null;
    public $insuranceSubType;
    public $insuranceSubTypes = [];
    public $insurances = [];
    public $insuranceId = null;

    public $is_closed = null;
    public $started_at = null;
    public $ended_at = null;
    public $questions = [];
    public $step = 0;
    public $standardSteps = 5;
    public $totalSteps = 0;
    public $answers = [];

    public function updatedInsuranceTypeId()
    {
        $this->insuranceType = InsuranceType::find($this->insuranceTypeId);
        $this->insuranceSubTypes = $this->insuranceType->subtypes()->get();
        
    }

    public function updatedInsuranceSubTypeId()
    {
        $this->insuranceSubType = InsuranceSubtype::find($this->insuranceSubTypeId);
        $this->insurances = $this->insuranceSubType->insurances()->get();
        $this->loadQuestions();
        
    }

    public function updatedInsuranceId()
    {
        $this->insurance = Insurance::find($this->insuranceId);
        
    }

    public function loadQuestions()
    {
        $this->questions = $this->insuranceSubType
            ->ratingQuestions()
            ->orderBy('insurance_subtype_rating_question.order_column')
            ->get();
        $this->totalSteps = $this->standardSteps + ($this->questions->count()); 
    }

    public function nextStep()
    {
        $this->validate($this->rules());
        $this->saveAnswers();
        if ($this->step < count($this->questions)+($this->standardSteps)) {
            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 0) {
            $this->step--;
        }
    }

    public function saveAnswers()
    {
        foreach ($this->questions as $question) {
            $key = (string) $question->id;
    
            // Sicherstellen, dass jede Frage im Array enthalten ist
            if (!array_key_exists($key, $this->answers)) {
                $this->answers[$key] = null;
            }
    
            $value = $this->answers[$key];
    
            switch ($question->type) {
                case 'boolean':
                    // true/false sicherstellen
                    $this->answers[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
    
                case 'number':
                    // Als Integer oder Float speichern, wenn numerisch
                    $this->answers[$key] = is_numeric($value) ? +$value : null;
                    break;
    
                case 'rating':
                    // Sternebewertung (z. B. 1–5) als Integer casten
                    $this->answers[$key] = is_numeric($value) ? (int)$value : null;
                    break;
    
                case 'date':
                    // Datum validieren und normalisieren (Y-m-d)
                    try {
                        $parsed = \Carbon\Carbon::parse($value);
                        $this->answers[$key] = $parsed->toDateString(); // '2025-04-22'
                    } catch (\Exception $e) {
                        $this->answers[$key] = null;
                    }
                    break;
    
                case 'select':
                    // Auswahl beibehalten (z. B. String-Wert aus Dropdown)
                    $this->answers[$key] = $value;
                    break;
    
                case 'text':
                default:
                    // Standard: String oder null
                    $this->answers[$key] = is_string($value) ? trim($value) : null;
                    break;
            }
        }
    }
    


    public function submit()
    {
        $this->validate($this->rules());
        $this->saveAnswers();
        $ratingquestionnaireversions = RatingQuestionnaireVersion::where('insurance_subtype_id', $this->insuranceSubTypeId)->latest()->first();
    
        $claimRating = ClaimRating::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'insurance_type_id' => $this->insuranceTypeId,
            'insurance_subtype_id' => $this->insuranceSubTypeId,
            'insurance_id' => $this->insuranceType->id,
            'rating_questionnaire_versions_id' => optional($ratingquestionnaireversions)->id,
            'answers' => $this->answers,
            'status' => 'pending',
            'attachments' => [], // Wenn du hier keine hochgeladenen Dateien hast
            'rating_score' => null, // Kannst du später berechnen
            'moderator_comment' => null,
            'is_public' => false,
            'verification_hash' => Str::uuid(),
        ]);
    
        // Weiterleitung auf eine Dankeseite oder Bestätigungsseite
        return redirect()->route('claim-rating.success', ['hash' => $claimRating->verification_hash]);
    }

    public function rules()
    {
        $rules = [];
        if ($this->step >= 0) {
            $rules['insuranceTypeId'] = 'required';
        }
        if ($this->step >= 1) {
            $rules['insuranceSubTypeId'] = 'required';
        }
        if ($this->step >= 2) {
            $rules['insuranceId'] = 'required';
        }
        if ($this->step >= 3) {
            $rules['is_closed'] = 'required|boolean';
        }
    
        if ($this->step >= 4) {
            $rules['started_at'] = 'required|date';
            if ($this->is_closed) {
                $rules['ended_at'] = 'required|date|after_or_equal:started_at';
            }
        }
    
        if ($this->step >= 5) {
            foreach ($this->questions as $q) {
                if ($q->is_required) {
                    $rules["answers.{$q->id}"] = 'required';
                }
            }
        }
    
        return $rules;
    }

    public function render()
    {
        $types = InsuranceType::whereHas('subtypes', function ($query) {
            $query->whereHas('latestVersion', function ($q) {
                $q->where('is_active', true);
            });
        })->get();
    
        return view('livewire.customer.rating.rating-form', [
            'types' => $types,
        ]);
    }
    
}

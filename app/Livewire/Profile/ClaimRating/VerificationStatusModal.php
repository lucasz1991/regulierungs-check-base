<?php

namespace App\Livewire\Profile\ClaimRating;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ClaimRating;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class VerificationStatusModal extends Component
{
    use WithFileUploads;

    public ClaimRating $claimRating;

    public bool $showModal = false;

    public ?string $caseNumber = null;

    /** @var array */
    public array $newFiles = [];

    protected function rules(): array
    {
        return [
            'caseNumber' => ['required', 'string', 'max:100'],
            'newFiles.*' => ['file', 'max:10240'], // 10 MB pro Datei
        ];
    }

    public function mount(ClaimRating $claimRating): void
    {
        abort_if($claimRating->user_id !== Auth::id(), 403);

        $this->claimRating = $claimRating;

        // bereits vorhandene Fallnummer aus data['verification']
        $this->caseNumber = $this->claimRating->verification['caseNumber'] ?? null;
    }
 
    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function submit(): void
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->validate();

        // neue Dateien speichern und anhängen (mehrere möglich)
        foreach ($this->newFiles as $upload) {
            $storedPath = $upload->store('claim-verification', 'private');

            $file = new File([
                'user_id'   => Auth::id(),
                'name'      => $upload->getClientOriginalName(),
                'path'      => $storedPath,
                'mime_type' => $upload->getMimeType(),
                'size'      => $upload->getSize(),
                'type'      => 'claim_verification',
            ]);

            $file->fileable()->associate($this->claimRating);
            $file->save();
        }

        // ClaimRating in Verifikations-Status setzen
        $this->claimRating->markVerificationPending($this->caseNumber);

        // neu laden (inkl. Verification + Files)
        $this->claimRating->refresh();

        // Uploads zurücksetzen
        $this->newFiles = [];

        session()->flash('message', 'Falldaten gespeichert und zur Verifikation eingereicht.');
    }

    /**
     * Für Tooltip / Anzeige: Label abhängig vom Verification-State.
     */
    public function getStatusLabelProperty(): string
    {
        $v = $this->claimRating->verification;

        return match ($v['state']) {
            'pending'  => 'In Prüfung',
            'approved' => 'Verifiziert',
            'rejected' => 'Abgelehnt',
            default    => 'Keine Verifikation',
        };
    }

    public function render()
    {
        $verificationFiles = $this->claimRating->verificationFiles()->get();

        return view('livewire.profile.claim-rating.verification-status-modal', [
            'verificationFiles' => $verificationFiles,
            'statusLabel'       => $this->statusLabel,
        ]);
    }
}

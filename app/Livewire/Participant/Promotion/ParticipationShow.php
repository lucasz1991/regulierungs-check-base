<?php

namespace App\Livewire\Participant\Promotion;

use App\Models\PromotionParticipation;
use App\Services\Promotion\PromotionWinService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class ParticipationShow extends Component
{
    #[Locked]
    public string $participationPublicId;

    public function mount(PromotionParticipation $participation): void
    {
        abort_unless((int) $participation->user_id === (int) Auth::id(), 404);

        $this->participationPublicId = $participation->public_id;
    }

    public function confirm(PromotionWinService $promotionWinService): void
    {
        $participation = $this->ownedParticipation();

        try {
            $promotionWinService->confirmParticipation($participation, Auth::user(), $this->accessContext());
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('participation', 'Der Gewinn konnte nicht bestätigt werden. Bitte lade die Seite neu oder wende dich an das Promotion-Team.');

            return;
        }

        session()->flash('promotion_success', 'Dein Gewinn wurde verbindlich bestätigt.');
    }

    public function dispute(PromotionWinService $promotionWinService): void
    {
        $participation = $this->ownedParticipation();

        try {
            $promotionWinService->disputeParticipation($participation, Auth::user(), $this->accessContext());
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('participation', 'Die Beanstandung konnte nicht gespeichert werden. Bitte versuche es erneut.');

            return;
        }

        session()->flash('promotion_success', 'Deine Beanstandung wurde gespeichert. Ein Administrator prüft den Vorgang.');
    }

    public function render()
    {
        $participation = $this->ownedParticipation()->loadMissing(['campaign', 'currentWin.prize']);

        return view('participant.promotion.participation-show', [
            'participation' => $participation,
            'status' => $this->statusValue($participation->status),
        ])->layout('layouts.app');
    }

    private function ownedParticipation(): PromotionParticipation
    {
        return PromotionParticipation::query()
            ->where('public_id', $this->participationPublicId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof \BackedEnum ? (string) $status->value : (string) $status;
    }

    /** @return array{ip_address: string|null, user_agent: string|null} */
    private function accessContext(): array
    {
        return [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
    }
}

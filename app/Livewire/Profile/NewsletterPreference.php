<?php

namespace App\Livewire\Profile;

use App\Services\NewsletterSubscriptions;
use Livewire\Attributes\Locked;
use Livewire\Component;

class NewsletterPreference extends Component
{
    #[Locked]
    public int $version;

    public string $notice = '';

    public function mount(NewsletterSubscriptions $subscriptions): void
    {
        $this->version = $this->record($subscriptions)->version;
    }

    private function record(NewsletterSubscriptions $subscriptions): object
    {
        abort_unless(auth()->check() && auth()->user()->isActive(), 403);

        return $subscriptions->forSource('user', auth()->id());
    }

    public function change(string $action, NewsletterSubscriptions $subscriptions): void
    {
        abort_unless(in_array($action, ['toggle', 'allow', 'greeting'], true), 422);
        $s = $this->record($subscriptions);
        $enabled = $action === 'toggle' ? ! $s->enabled : (bool) $s->enabled;
        $proof = $enabled && in_array($action, ['allow', 'toggle'], true) ? NewsletterSubscriptions::AGREEMENT : null;
        $subscriptions->update($s->id, $this->version, $enabled, $action === 'greeting' ? ! $s->neutral_greeting : (bool) $s->neutral_greeting,
            'own_profile', auth()->id(), $proof);
        $this->version = $this->record($subscriptions)->version;
        $this->notice = 'Einstellungen gespeichert.';
    }

    public function render(NewsletterSubscriptions $subscriptions)
    {
        return view('livewire.profile.newsletter-preference', ['subscription' => $this->record($subscriptions)]);
    }
}

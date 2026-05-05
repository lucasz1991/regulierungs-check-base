<?php

namespace App\Livewire;

use Livewire\Component;

class UserAlert extends Component
{
    public $message = 'Standardnachricht';
    public $type = 'info';

    public array $typeLabels = [
        'info' => 'Information',
        'success' => 'Erfolgreich',
        'warning' => 'Warnung',
        'error' => 'Fehler',
        'danger' => 'Achtung',
        'question' => 'Frage',
        'notice' => 'Hinweis',
    ];

    protected $listeners = [
        'showAlert' => 'displayAlert',
        'toast' => 'displayToast',
    ];

    public function mount(): void
    {
        if (session()->has('success')) {
            $this->displayToast(session('success'), 'success');
            return;
        }

        if (session()->has('error')) {
            $this->displayAlert(session('error'), 'error');
            return;
        }

        if (session()->has('message')) {
            $this->displayAlert(
                session('message'),
                session('messageType', 'info')
            );
        }
    }

    public function displayToast($message, $type = 'info', array $options = []): void
    {
        $payload = $this->normalizePayload($message, $type, $options);

        $this->dispatch(
            'swal:toast',
            type: $payload['type'],
            title: $payload['title'],
            text: $payload['text'] ?? null,
            html: $payload['html'] ?? null,
            position: $payload['position'] ?? null,
            timer: $payload['timer'] ?? null,
            redirectTo: $payload['redirectTo'] ?? null,
            confirmText: $payload['confirmText'] ?? 'OK',
            showConfirm: $payload['showConfirm'] ?? null,
        );
    }

    public function displayAlert($message, $type = 'info', array $options = []): void
    {
        $payload = $this->normalizePayload($message, $type, $options);

        $this->dispatch(
            'swal:alert',
            type: $payload['type'],
            title: $payload['title'],
            text: $payload['text'] ?? null,
            html: $payload['html'] ?? null,
            showCancel: $payload['showCancel'] ?? false,
            confirmText: $payload['confirmText'] ?? 'OK',
            cancelText: $payload['cancelText'] ?? 'Abbrechen',
            allowOutsideClick: $payload['allowOutsideClick'] ?? true,
            onConfirm: $payload['onConfirm'] ?? null,
            redirectTo: $payload['redirectTo'] ?? null,
            redirectOn: $payload['redirectOn'] ?? 'confirm',
        );
    }

    protected function normalizePayload($message, string $type, array $options = []): array
    {
        $payload = is_array($message)
            ? array_merge($message, $options)
            : array_merge($this->normalizeMessageContent($message), $options);

        if (array_key_exists('message', $payload) && ! array_key_exists('text', $payload) && ! array_key_exists('html', $payload)) {
            $payload = array_merge(
                $payload,
                $this->normalizeMessageContent((string) $payload['message'])
            );
        }

        $typeKey = $payload['type'] ?? $type;

        $payload['type'] = $typeKey;
        $payload['title'] = $payload['title'] ?? ($this->typeLabels[$typeKey] ?? ucfirst($typeKey));

        return $payload;
    }

    protected function normalizeMessageContent(string $message): array
    {
        if ($this->containsHtml($message)) {
            return ['html' => $message];
        }

        return ['text' => $message];
    }

    protected function containsHtml(string $message): bool
    {
        return $message !== strip_tags($message);
    }

    public function render()
    {
        return view('livewire.user-alert');
    }
}

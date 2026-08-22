<?php

namespace App\Livewire\Tools;

use App\Models\Setting;
use App\Support\MilanScopePolicy;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Session;
use Livewire\Component;

class Chatbot extends Component
{
    private const PENDING_NAVIGATION_SESSION_KEY = 'milan.pending_navigation_target';

    private const MODEL_HISTORY_SESSION_KEY = 'milan.model_history';

    private const OPEN_RATING_FORM_SESSION_KEY = 'milan.open_rating_form';

    private const RATE_LIMIT_MAX_ATTEMPTS = 12;

    private const RATE_LIMIT_DECAY_SECONDS = 60;

    private const DEFAULT_DAILY_LIMIT = 20;

    private const RATE_LIMIT_LOCK_SECONDS = 5;

    private const RATE_LIMIT_LOCK_WAIT_SECONDS = 2;

    #[Session]
    public $chatHistory;

    #[Session]
    public $lastResponse;

    #[Session(key: 'showChat')]
    public $showChat;

    public $isLoading = false;

    public $isFunctionCall = false;

    public $status;

    public $assistantName;

    public function mount(): void
    {
        if (! is_array($this->chatHistory)) {
            $this->chatHistory = [];
        }

        $settings = $this->freshAiSettings();
        $this->status = $this->settingIsEnabled($settings['status'] ?? false);
        $this->assistantName = $this->settingString($settings['assistant_name'] ?? null);
    }

    public function sendMessage(string $message = ''): void
    {
        $userMessage = trim($message);

        if ($userMessage === '') {
            return;
        }

        $this->isLoading = true;
        $this->chatHistory = MilanScopePolicy::sanitizeHistory($this->chatHistory);

        if (mb_strlen($userMessage) > MilanScopePolicy::MAX_MESSAGE_LENGTH) {
            $this->removeOversizedDisplayMessage($userMessage);
            $this->appendLocalResponse($this->messageTooLongResponse(), recordInModelHistory: false);

            return;
        }

        $this->appendUserMessageIfMissing($userMessage);

        $settings = $this->freshAiSettings();

        if (! $this->settingIsEnabled($settings['status'] ?? false)) {
            $this->appendLocalResponse($this->unavailableResponse(), recordInModelHistory: false);

            return;
        }

        $trustedHistory = $this->trustedModelHistory();
        $pendingTarget = $this->pendingNavigationTarget();

        if ($pendingTarget !== null && MilanScopePolicy::isExplicitConfirmation($userMessage)) {
            $this->recordTrustedMessage('user', $userMessage);
            $this->confirmPendingNavigation($pendingTarget);

            return;
        }

        if ($pendingTarget !== null && MilanScopePolicy::isExplicitRejection($userMessage)) {
            $this->clearPendingNavigation();
            $this->recordTrustedMessage('user', $userMessage);
            $this->appendLocalResponse($this->navigationCancelledResponse());

            return;
        }

        if ($pendingTarget !== null) {
            $this->clearPendingNavigation();
        }

        if (MilanScopePolicy::mustRefuse($userMessage)
            || ! MilanScopePolicy::isWithinScope($userMessage, $trustedHistory)
        ) {
            $this->appendLocalResponse(MilanScopePolicy::restrictedResponse(), recordInModelHistory: false);

            return;
        }

        $cannedAnswer = MilanScopePolicy::cannedAnswer($userMessage);

        if ($cannedAnswer !== null) {
            $this->recordTrustedMessage('user', $userMessage);
            $this->appendLocalResponse($this->informationalResponse($cannedAnswer, 'Smalltalk'));

            return;
        }

        $apiUrl = $this->settingString($settings['api_url'] ?? null);
        $apiKey = $this->settingString($settings['api_key'] ?? null);
        $aiModel = $this->settingString($settings['ai_model'] ?? null);

        if ($apiUrl === '' || $apiKey === '' || $aiModel === '') {
            Log::error('Milan-Konfiguration ist unvollständig.');
            $this->appendLocalResponse($this->unavailableResponse());

            return;
        }

        $dailyLimitKey = $this->dailyRateLimitKey();
        $dailyLimit = $this->dailyLimit();
        $burstLimitKey = $this->burstRateLimitKey();
        $quotaResponse = $this->reserveProviderQuota($dailyLimitKey, $dailyLimit, $burstLimitKey);

        if ($quotaResponse !== null) {
            $this->appendLocalResponse($quotaResponse, recordInModelHistory: false);

            return;
        }

        $this->recordTrustedMessage('user', $userMessage);

        Log::info('Milan-Anfrage empfangen.', [
            'message_length' => mb_strlen($userMessage),
        ]);

        $maxRetries = 3;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $response = Http::connectTimeout(5)->timeout(30)->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                    'HTTP-Referer' => $this->settingString($settings['referer_url'] ?? null),
                    'X-Title' => $this->settingString($settings['model_title'] ?? null),
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, [
                    'model' => $aiModel,
                    'max_tokens' => 160,
                    'temperature' => 0.2,
                    'messages' => array_merge([
                        [
                            'role' => 'system',
                            'content' => $this->systemPrompt(
                                $this->settingString($settings['train_content'] ?? null)
                            ),
                        ],
                    ], $this->messagesForModel($userMessage)),
                    'response_format' => $this->responseFormat(),
                ]);

                if (! $response->successful()) {
                    Log::warning('Milan-API antwortete mit einem Fehlerstatus.', [
                        'status' => $response->status(),
                    ]);

                    continue;
                }

                $content = $response->json('choices.0.message.content');
                $decoded = is_string($content) ? json_decode($content, true) : null;

                if (! is_array($decoded)) {
                    Log::warning('Milan-API lieferte keine gültige strukturierte Antwort.');

                    continue;
                }

                $decoded = $this->enforceModelResponse($decoded);
                $this->appendLocalResponse($decoded);

                return;
            } catch (\Throwable $exception) {
                Log::error('Fehler bei der Milan-API-Anfrage.', [
                    'exception' => $exception::class,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        $this->appendLocalResponse($this->unavailableResponse());
    }

    protected function navigationTargets(bool $includeEmpty = false): array
    {
        $targets = ['home', 'reviews', 'insurances', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating'];

        if ($this->blogEnabled()) {
            array_splice($targets, 3, 0, ['blog']);
        }

        return $includeEmpty ? ['', ...$targets] : $targets;
    }

    protected function systemPrompt(string $trainContent): string
    {
        $sections = [];
        $trainContent = trim($trainContent);

        if ($trainContent !== '') {
            $sections[] = $trainContent;
        }

        if (! $this->blogEnabled()) {
            $sections[] = 'Der öffentliche Blog ist derzeit deaktiviert. Biete keine Blog-Inhalte und keine Navigation zum Blog an.';
        }

        $sections[] = MilanScopePolicy::systemInstructions();

        return implode("\n\n", $sections);
    }

    protected function messagesForModel(string $userMessage): array
    {
        $history = $this->trustedModelHistory();
        $lastMessage = $history === [] ? null : $history[array_key_last($history)];

        if (($lastMessage['role'] ?? null) !== 'user'
            || trim((string) ($lastMessage['content'] ?? '')) !== $userMessage
        ) {
            $history[] = [
                'role' => 'user',
                'content' => $userMessage,
            ];
        }

        return $history;
    }

    public function clearChat(): void
    {
        $this->chatHistory = [];
        $this->lastResponse = null;
        $this->clearPendingNavigation();
        session()->forget(self::MODEL_HISTORY_SESSION_KEY);
    }

    public function render()
    {
        return view('livewire.tools.chatbot');
    }

    private function responseFormat(): array
    {
        return [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => 'AnswerData',
                'strict' => true,
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'response_topic' => [
                            'type' => 'string',
                            'enum' => MilanScopePolicy::RESPONSE_TOPICS,
                            'description' => 'Klassifikation ohne freien Antworttext: platform_overview für Zweck und Ziel der Plattform; how_it_works für Ablauf; rating_participation für Teilnahme und Bewertung; scores_and_analysis für Scores, Freitexte und Analyse; filters_and_results für Filter und Ergebnisse; privacy für Datenschutz und Anonymität; benefits für Nutzen; validity für Aussagekraft und Grenzen; claims_orientation nur für neutrale allgemeine Orientierung; website_navigation für Website-Bereiche; restricted für alles Sachfremde, individuelle Beratung oder Texterstellung.',
                        ],
                        'request_scope' => [
                            'type' => 'string',
                            'enum' => ['allowed', 'restricted'],
                            'description' => 'allowed nur für Informationen und Funktionen rund um Regulierungs-CHECK; sonst restricted.',
                        ],
                        'function_name' => [
                            'type' => 'string',
                            'enum' => ['none', 'navigate'],
                            'description' => 'Bei einem Vorschlag immer none. navigate wird nicht durch das Modell selbst ausgeführt.',
                        ],
                        'function_value' => [
                            'type' => 'string',
                            'enum' => $this->navigationTargets(includeEmpty: true),
                            'description' => 'Nur bei einer bestätigten Navigation belegt, sonst leer.',
                        ],
                        'function_trigger' => [
                            'type' => 'boolean',
                            'description' => 'Bei Modellantworten und Vorschlägen false; die Anwendung bestätigt Navigation serverseitig.',
                        ],
                        'proposed_navigation' => [
                            'type' => 'string',
                            'enum' => $this->navigationTargets(includeEmpty: true),
                            'description' => 'Konkretes erlaubtes Ziel eines höflich formulierten Navigationsvorschlags, sonst leer.',
                        ],
                    ],
                    'required' => [
                        'response_topic',
                        'request_scope',
                        'function_name',
                        'function_value',
                        'function_trigger',
                        'proposed_navigation',
                    ],
                    'additionalProperties' => false,
                ],
            ],
        ];
    }

    private function enforceModelResponse(array $response): array
    {
        $rawAnswer = is_string($response['answer'] ?? null) ? trim($response['answer']) : '';
        $topic = is_string($response['response_topic'] ?? null)
            ? $response['response_topic']
            : '';
        $proposal = $this->validatedNavigationTarget($response['proposed_navigation'] ?? null);
        $modelTriggeredNavigation = ($response['function_trigger'] ?? false) === true;

        if ($modelTriggeredNavigation) {
            $proposal ??= $this->validatedNavigationTarget($response['function_value'] ?? null);
        }

        if (($response['request_scope'] ?? null) !== 'allowed'
            || ! in_array($topic, MilanScopePolicy::RESPONSE_TOPICS, true)
            || $topic === 'restricted'
            || ($proposal === null && $rawAnswer !== '' && (
                MilanScopePolicy::looksLikeGeneratedArtifact($rawAnswer)
                || MilanScopePolicy::looksLikeIndividualAdvice($rawAnswer)
                || ! MilanScopePolicy::isAnswerWithinScope($rawAnswer)
            ))
        ) {
            return MilanScopePolicy::restrictedResponse();
        }

        if ($proposal !== null) {
            $answer = $this->navigationProposalQuestion($proposal);
            session()->put(self::PENDING_NAVIGATION_SESSION_KEY, $proposal);
        } else {
            $answer = MilanScopePolicy::answerForTopic($topic);
        }

        if ($answer === null) {
            return MilanScopePolicy::restrictedResponse();
        }

        return [
            'answer' => $answer,
            'response_topic' => $topic,
            'request_scope' => 'allowed',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => $proposal ?? '',
            'sentiment' => in_array($response['sentiment'] ?? null, ['neutral', 'positiv', 'negativ'], true)
                ? $response['sentiment']
                : 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Serverantwort', $topic],
        ];
    }

    private function freshAiSettings(): array
    {
        return Setting::query()
            ->where('type', 'ai_assistant')
            ->whereIn('key', [
                'status',
                'assistant_name',
                'api_url',
                'api_key',
                'ai_model',
                'model_title',
                'referer_url',
                'train_content',
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->unique('key')
            ->mapWithKeys(fn (Setting $setting): array => [$setting->key => $setting->value])
            ->all();
    }

    private function settingString(mixed $value): string
    {
        if (is_array($value)) {
            $value = $value['value'] ?? reset($value);
        }

        return is_scalar($value) ? trim((string) $value) : '';
    }

    private function settingIsEnabled(mixed $value): bool
    {
        if (is_array($value)) {
            $value = $value['enabled'] ?? $value['value'] ?? reset($value);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private function appendUserMessageIfMissing(string $message): void
    {
        $lastMessage = $this->chatHistory === []
            ? null
            : $this->chatHistory[array_key_last($this->chatHistory)];

        if (($lastMessage['role'] ?? null) === 'user'
            && trim((string) ($lastMessage['content'] ?? '')) === $message
        ) {
            return;
        }

        $this->chatHistory[] = [
            'role' => 'user',
            'content' => $message,
        ];
    }

    private function appendLocalResponse(array $response, bool $recordInModelHistory = true): void
    {
        $this->lastResponse = $response;
        $this->chatHistory[] = [
            'role' => 'assistant',
            'content' => (string) ($response['answer'] ?? ''),
        ];

        if ($recordInModelHistory) {
            $this->recordTrustedMessage('assistant', (string) ($response['answer'] ?? ''));
        }

        $this->isLoading = false;
    }

    private function removeOversizedDisplayMessage(string $message): void
    {
        if ($this->chatHistory === []) {
            return;
        }

        $lastKey = array_key_last($this->chatHistory);
        $lastMessage = $this->chatHistory[$lastKey];
        $lastContent = trim((string) ($lastMessage['content'] ?? ''));

        if (($lastMessage['role'] ?? null) === 'user'
            && $lastContent !== ''
            && ($lastContent === $message
                || (mb_strlen($lastContent) === MilanScopePolicy::MAX_HISTORY_MESSAGE_LENGTH
                    && str_starts_with($message, $lastContent)))
        ) {
            unset($this->chatHistory[$lastKey]);
            $this->chatHistory = array_values($this->chatHistory);
        }
    }

    private function trustedModelHistory(): array
    {
        return MilanScopePolicy::sanitizeHistory(
            session()->get(self::MODEL_HISTORY_SESSION_KEY, [])
        );
    }

    private function recordTrustedMessage(string $role, string $content): void
    {
        $history = $this->trustedModelHistory();
        $history[] = [
            'role' => $role,
            'content' => $content,
        ];

        session()->put(
            self::MODEL_HISTORY_SESSION_KEY,
            MilanScopePolicy::sanitizeHistory($history)
        );
    }

    private function burstRateLimitKey(): string
    {
        $userId = auth()->id();
        $identity = $userId !== null
            ? 'user|'.(string) $userId
            : 'guest|'.session()->getId().'|'.(string) request()->ip();

        return 'milan-chat:burst:'.$this->hashLimiterIdentity($identity);
    }

    private function dailyRateLimitKey(): string
    {
        $userId = auth()->id();
        $scope = $userId !== null ? 'user' : 'guest';
        $identity = $userId !== null
            ? 'user|'.(string) $userId
            : 'guest-ip|'.(string) request()->ip();

        return 'milan-chat:daily:'.now()->format('Y-m-d').':'.$scope.':'.$this->hashLimiterIdentity($identity);
    }

    private function dailyLimit(): int
    {
        $configKey = auth()->id() !== null
            ? 'services.milan.user_daily_limit'
            : 'services.milan.guest_daily_limit';

        return max(1, (int) config($configKey, self::DEFAULT_DAILY_LIMIT));
    }

    private function dailyRateLimitDecaySeconds(): int
    {
        $now = now();
        $nextMidnight = $now->copy()->addDay()->startOfDay();

        return max(1, $nextMidnight->getTimestamp() - $now->getTimestamp());
    }

    private function hashLimiterIdentity(string $identity): string
    {
        return hash_hmac('sha256', $identity, (string) config('app.key'));
    }

    private function reserveProviderQuota(
        string $dailyLimitKey,
        int $dailyLimit,
        string $burstLimitKey
    ): ?array {
        try {
            return Cache::lock(
                'milan-chat:quota-lock:'.$this->hashLimiterIdentity($dailyLimitKey),
                self::RATE_LIMIT_LOCK_SECONDS
            )->block(self::RATE_LIMIT_LOCK_WAIT_SECONDS, function () use (
                $dailyLimitKey,
                $dailyLimit,
                $burstLimitKey
            ): ?array {
                if (RateLimiter::tooManyAttempts($dailyLimitKey, $dailyLimit)) {
                    return $this->dailyRateLimitedResponse($dailyLimit);
                }

                if (RateLimiter::tooManyAttempts($burstLimitKey, self::RATE_LIMIT_MAX_ATTEMPTS)) {
                    return $this->rateLimitedResponse();
                }

                $burstAttempts = RateLimiter::hit($burstLimitKey, self::RATE_LIMIT_DECAY_SECONDS);

                if ($burstAttempts > self::RATE_LIMIT_MAX_ATTEMPTS) {
                    return $this->rateLimitedResponse();
                }

                $dailyAttempts = RateLimiter::hit($dailyLimitKey, $this->dailyRateLimitDecaySeconds());

                return $dailyAttempts > $dailyLimit
                    ? $this->dailyRateLimitedResponse($dailyLimit)
                    : null;
            });
        } catch (LockTimeoutException) {
            return $this->rateLimitedResponse();
        }
    }

    private function pendingNavigationTarget(): ?string
    {
        $target = $this->validatedNavigationTarget(
            session()->get(self::PENDING_NAVIGATION_SESSION_KEY)
        );

        if ($target === null) {
            $this->clearPendingNavigation();
        }

        return $target;
    }

    private function clearPendingNavigation(): void
    {
        session()->forget(self::PENDING_NAVIGATION_SESSION_KEY);
    }

    private function validatedNavigationTarget(mixed $target): ?string
    {
        return is_string($target) && in_array($target, $this->navigationTargets(), true)
            ? $target
            : null;
    }

    private function confirmPendingNavigation(string $target): void
    {
        $this->clearPendingNavigation();
        $this->appendLocalResponse([
            'answer' => $this->navigationConfirmation($target),
            'response_topic' => 'website_navigation',
            'request_scope' => 'allowed',
            'function_name' => 'navigate',
            'function_value' => $target,
            'function_trigger' => true,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Navigation'],
        ]);

        if ($target === 'home') {
            $this->redirect('/', navigate: true);

            return;
        }

        if ($target === '#start-rating') {
            session()->flash(self::OPEN_RATING_FORM_SESSION_KEY, true);
            $this->redirect('/', navigate: true);

            return;
        }

        $this->redirect(url($target), navigate: true);
    }

    private function navigationProposalQuestion(string $target): string
    {
        return match ($target) {
            'home' => 'Möchtest du zur Startseite gehen?',
            'reviews' => 'Möchtest du die Bewertungen ansehen?',
            'insurances' => 'Möchtest du die Versicherungsübersicht ansehen?',
            'blog' => 'Möchtest du den Blog ansehen?',
            'aboutus' => 'Möchtest du mehr über Regulierungs-CHECK erfahren?',
            'guidance' => 'Möchtest du die Vorschau zur Beratung ansehen?',
            'howto' => 'Möchtest du „So funktioniert’s“ öffnen?',
            'contact' => 'Möchtest du die Kontaktseite öffnen?',
            '#start-rating' => 'Möchtest du den Bewertungsfragebogen öffnen?',
            default => 'Möchtest du zu diesem Bereich weitergeleitet werden?',
        };
    }

    private function navigationConfirmation(string $target): string
    {
        return match ($target) {
            'home' => 'Ich leite dich zur Startseite weiter.',
            'reviews' => 'Ich leite dich zu den Bewertungen weiter.',
            'insurances' => 'Ich leite dich zur Versicherungsübersicht weiter.',
            'blog' => 'Ich leite dich zum Blog weiter.',
            'aboutus' => 'Ich leite dich zu den Informationen über Regulierungs-CHECK weiter.',
            'guidance' => 'Ich leite dich zur Vorschau der Beratung weiter.',
            'howto' => 'Ich leite dich zu „So funktioniert’s“ weiter.',
            'contact' => 'Ich leite dich zur Kontaktseite weiter.',
            '#start-rating' => 'Ich öffne den Bewertungsfragebogen.',
            default => 'Ich leite dich weiter.',
        };
    }

    private function blogEnabled(): bool
    {
        return Setting::enabled('webcontent', 'blog_enabled', false);
    }

    private function messageTooLongResponse(): array
    {
        return [
            'answer' => 'Bitte kürze deine Frage auf höchstens 1.200 Zeichen und beschränke sie auf Regulierungs-CHECK.',
            'response_topic' => 'restricted',
            'request_scope' => 'restricted',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Eingabelimit'],
        ];
    }

    private function unavailableResponse(): array
    {
        return [
            'answer' => 'Der Regulierungs-CHECK Assistent ist derzeit nicht verfügbar. Bitte versuche es später noch einmal.',
            'response_topic' => 'restricted',
            'request_scope' => 'restricted',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Nicht verfügbar'],
        ];
    }

    private function informationalResponse(string $answer, string $tag): array
    {
        return [
            'answer' => $answer,
            'response_topic' => 'platform_overview',
            'request_scope' => 'allowed',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => [$tag],
        ];
    }

    private function rateLimitedResponse(): array
    {
        return [
            'answer' => 'Du hast gerade sehr viele Fragen gesendet. Bitte warte kurz und versuche es dann noch einmal.',
            'response_topic' => 'restricted',
            'request_scope' => 'restricted',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Rate-Limit'],
        ];
    }

    private function dailyRateLimitedResponse(int $dailyLimit): array
    {
        return [
            'answer' => 'Du hast dein tägliches Milan-Kontingent von '.$dailyLimit.' KI-Anfragen erreicht. Ab Mitternacht kannst du Milan wieder verwenden.',
            'response_topic' => 'restricted',
            'request_scope' => 'restricted',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Tageslimit'],
        ];
    }

    private function navigationCancelledResponse(): array
    {
        return [
            'answer' => 'Alles klar, ich leite dich nicht weiter.',
            'response_topic' => 'website_navigation',
            'request_scope' => 'allowed',
            'function_name' => 'none',
            'function_value' => '',
            'function_trigger' => false,
            'proposed_navigation' => '',
            'sentiment' => 'neutral',
            'call_to_action' => 'none',
            'tags' => ['Navigation'],
        ];
    }
}

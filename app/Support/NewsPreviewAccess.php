<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsonException;
use Throwable;

final class NewsPreviewAccess
{
    public const REQUEST_ATTRIBUTE = 'news_admin_preview';

    /**
     * Stable API for user-facing components that need to include draft News.
     */
    public static function isActive(?Request $request = null): bool
    {
        $request ??= self::currentRequest();

        if ($request === null) {
            return false;
        }

        if ($request->attributes->has(self::REQUEST_ATTRIBUTE)) {
            return (bool) $request->attributes->get(self::REQUEST_ATTRIBUTE);
        }

        return self::verifiedPayload($request) !== null;
    }

    /**
     * @return array{admin_user_id:int, admin_session_id:string, issued:int, expires:int}|null
     */
    public static function verifiedPayload(Request $request): ?array
    {
        $token = $request->cookies->get((string) config('news-preview.cookie_name', 'rc_news_preview'));
        $payload = is_string($token) ? self::decodeAndVerify($token) : null;

        if ($payload === null || ! self::hasActiveAdminAndSession($payload)) {
            $request->attributes->set(self::REQUEST_ATTRIBUTE, false);

            return null;
        }

        $request->attributes->set(self::REQUEST_ATTRIBUTE, true);
        $request->attributes->set('news_admin_preview_user_id', $payload['admin_user_id']);

        return $payload;
    }

    /**
     * @return array{admin_user_id:int, admin_session_id:string, issued:int, expires:int}|null
     */
    private static function decodeAndVerify(string $token): ?array
    {
        $secret = self::secret();

        if ($secret === null || substr_count($token, '.') !== 1) {
            return null;
        }

        [$encodedPayload, $encodedSignature] = explode('.', $token, 2);
        $signature = self::base64UrlDecode($encodedSignature);

        if ($encodedPayload === '' || $signature === null) {
            return null;
        }

        $expected = hash_hmac('sha256', $encodedPayload, $secret, true);

        if (! hash_equals($expected, $signature)) {
            return null;
        }

        $json = self::base64UrlDecode($encodedPayload);

        if ($json === null) {
            return null;
        }

        try {
            $payload = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (! is_array($payload)
            || ! is_int($payload['admin_user_id'] ?? null)
            || $payload['admin_user_id'] < 1
            || ! is_string($payload['admin_session_id'] ?? null)
            || $payload['admin_session_id'] === ''
            || strlen($payload['admin_session_id']) > 255
            || ! is_int($payload['issued'] ?? null)
            || ! is_int($payload['expires'] ?? null)) {
            return null;
        }

        $now = time();
        $clockSkew = max(0, (int) config('news-preview.clock_skew_seconds', 60));

        if ($payload['issued'] > $now + $clockSkew
            || $payload['expires'] <= $now
            || $payload['expires'] <= $payload['issued']) {
            return null;
        }

        return [
            'admin_user_id' => $payload['admin_user_id'],
            'admin_session_id' => $payload['admin_session_id'],
            'issued' => $payload['issued'],
            'expires' => $payload['expires'],
        ];
    }

    /**
     * @param array{admin_user_id:int, admin_session_id:string, issued:int, expires:int} $payload
     */
    private static function hasActiveAdminAndSession(array $payload): bool
    {
        try {
            $activeAdmin = User::query()
                ->whereKey($payload['admin_user_id'])
                ->where('role', 'admin')
                ->where('status', 1)
                ->exists();

            if (! $activeAdmin) {
                return false;
            }

            $connection = config('news-preview.session_connection');
            $table = (string) config('news-preview.session_table', 'sessions');
            $idleSeconds = max(60, (int) config('news-preview.session_idle_minutes', 120) * 60);

            return DB::connection(is_string($connection) && $connection !== '' ? $connection : null)
                ->table($table)
                ->where('id', $payload['admin_session_id'])
                ->where('user_id', $payload['admin_user_id'])
                ->where('last_activity', '>=', time() - $idleSeconds)
                ->exists();
        } catch (Throwable) {
            return false;
        }
    }

    private static function secret(): ?string
    {
        $secret = trim((string) config('news-preview.shared_secret'));

        if ($secret !== '') {
            return $secret;
        }

        if (! config('news-preview.allow_app_key_fallback', false) && ! app()->environment('testing')) {
            return null;
        }

        $appKey = trim((string) config('app.key'));

        return $appKey !== '' ? $appKey : null;
    }

    private static function base64UrlDecode(string $value): ?string
    {
        if ($value === '' || preg_match('/[^A-Za-z0-9_-]/', $value)) {
            return null;
        }

        $padding = (4 - strlen($value) % 4) % 4;
        $decoded = base64_decode(strtr($value.str_repeat('=', $padding), '-_', '+/'), true);

        return $decoded === false ? null : $decoded;
    }

    private static function currentRequest(): ?Request
    {
        if (! app()->bound('request')) {
            return null;
        }

        $request = app('request');

        return $request instanceof Request ? $request : null;
    }
}

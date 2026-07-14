<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class PublicNewsCache
{
    public const TTL_SECONDS = 60;

    private const SETTING_TYPE = 'webcontent';

    private const SETTING_KEY = 'news_cache_version';

    /**
     * The generation is stored in the shared database so changes made by the
     * Admin application invalidate Base cache keys despite separate cache
     * stores. Old generations expire naturally after the short TTL.
     */
    public function generation(): string
    {
        $generation = DB::table('settings')
            ->where('type', self::SETTING_TYPE)
            ->where('key', self::SETTING_KEY)
            ->latest('updated_at')
            ->latest('id')
            ->value('value');

        return filled($generation) ? (string) $generation : '1';
    }

    public function remember(
        string $scope,
        array $context,
        Closure $resolver,
        ?string $generation = null
    ): mixed {
        return Cache::remember(
            $this->key($scope, $context, $generation),
            now()->addSeconds(self::TTL_SECONDS),
            $resolver
        );
    }

    public function key(string $scope, array $context, ?string $generation = null): string
    {
        $payload = [
            'generation' => $generation ?? $this->generation(),
            'locale' => app()->getLocale(),
            'context' => $this->normalize($context),
        ];

        $encodedPayload = json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );

        return sprintf(
            'webcontent.news.%s.%s',
            preg_replace('/[^a-z0-9._-]+/i', '-', $scope),
            hash('sha256', $encodedPayload)
        );
    }

    private function normalize(array $value): array
    {
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->normalize($item);
            }
        }

        if (! array_is_list($value)) {
            ksort($value);
        }

        return $value;
    }
}

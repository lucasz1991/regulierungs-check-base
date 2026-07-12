<?php

namespace Tests\Unit;

use App\Http\Middleware\DetectNewsPreviewAccess;
use App\Support\NewsPreviewAccess;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class NewsPreviewAccessTest extends TestCase
{
    private const CONNECTION = 'news_preview_testing';
    private const SECRET = 'unit-test-shared-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => self::CONNECTION,
            'database.connections.'.self::CONNECTION => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'news-preview.shared_secret' => self::SECRET,
            'news-preview.session_connection' => self::CONNECTION,
            'news-preview.session_table' => 'sessions',
            'news-preview.session_idle_minutes' => 120,
            'news-preview.clock_skew_seconds' => 60,
        ]);

        DB::purge(self::CONNECTION);

        Schema::connection(self::CONNECTION)->create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('role');
            $table->boolean('status');
        });

        Schema::connection(self::CONNECTION)->create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('last_activity');
        });

        DB::connection(self::CONNECTION)->table('users')->insert([
            'id' => 42,
            'role' => 'admin',
            'status' => 1,
        ]);
        DB::connection(self::CONNECTION)->table('sessions')->insert([
            'id' => 'active-admin-session',
            'user_id' => 42,
            'last_activity' => time(),
        ]);
    }

    protected function tearDown(): void
    {
        DB::purge(self::CONNECTION);

        parent::tearDown();
    }

    public function test_signed_proof_requires_an_active_admin_and_matching_database_session(): void
    {
        $request = $this->requestWithToken($this->token());

        $this->assertTrue(NewsPreviewAccess::isActive($request));
        $this->assertTrue($request->attributes->get('news_admin_preview'));
        $this->assertSame(42, $request->attributes->get('news_admin_preview_user_id'));
    }

    public function test_shared_app_key_is_an_automatic_fallback(): void
    {
        config([
            'news-preview.shared_secret' => null,
            'news-preview.allow_app_key_fallback' => true,
            'app.key' => 'base64:shared-app-key-for-preview',
        ]);

        $request = $this->requestWithToken($this->token(secret: 'base64:shared-app-key-for-preview'));

        $this->assertTrue(NewsPreviewAccess::isActive($request));
    }

    public function test_tampered_expired_and_stale_session_proofs_are_rejected(): void
    {
        $tampered = $this->requestWithToken($this->token().'x');
        $expired = $this->requestWithToken($this->token(time() - 300, time() - 60));

        DB::connection(self::CONNECTION)
            ->table('sessions')
            ->where('id', 'active-admin-session')
            ->update(['last_activity' => time() - 10_000]);
        $stale = $this->requestWithToken($this->token());

        $this->assertFalse(NewsPreviewAccess::isActive($tampered));
        $this->assertFalse(NewsPreviewAccess::isActive($expired));
        $this->assertFalse(NewsPreviewAccess::isActive($stale));
    }

    public function test_preview_middleware_shares_state_and_disables_indexing_and_caching(): void
    {
        $request = $this->requestWithToken($this->token());

        $response = (new DetectNewsPreviewAccess())->handle(
            $request,
            fn () => new Response('preview'),
        );

        $this->assertTrue($request->attributes->get('news_admin_preview'));
        $this->assertTrue(View::shared('newsAdminPreview'));
        $this->assertTrue(View::shared('news_admin_preview'));
        $this->assertSame('noindex, nofollow', $response->headers->get('X-Robots-Tag'));
        $this->assertStringContainsString('private', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    private function requestWithToken(string $token): Request
    {
        $request = Request::create('https://www.regulierungs-check.de/news');
        $request->cookies->set('rc_news_preview', $token);

        return $request;
    }

    private function token(?int $issued = null, ?int $expires = null, string $secret = self::SECRET): string
    {
        $issued ??= time() - 10;
        $expires ??= time() + 600;
        $payload = [
            'admin_user_id' => 42,
            'admin_session_id' => 'active-admin-session',
            'issued' => $issued,
            'expires' => $expires,
        ];
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $signature = hash_hmac('sha256', $encodedPayload, $secret, true);

        return $encodedPayload.'.'.$this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

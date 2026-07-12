<?php

return [
    /*
    | The secret must be identical in the admin and base applications in
    | production. APP_KEY is only accepted as a convenience in local/tests.
    */
    'shared_secret' => env('NEWS_PREVIEW_SHARED_SECRET'),
    'allow_app_key_fallback' => env('APP_ENV', 'production') === 'local',

    'cookie_name' => 'rc_news_preview',
    'cookie_domain' => env('NEWS_PREVIEW_COOKIE_DOMAIN', '.regulierungs-check.de'),

    'session_connection' => env('NEWS_PREVIEW_SESSION_CONNECTION', env('SESSION_CONNECTION')),
    'session_table' => env('NEWS_PREVIEW_SESSION_TABLE', 'sessions'),
    'session_idle_minutes' => (int) env('NEWS_PREVIEW_SESSION_IDLE_MINUTES', env('SESSION_LIFETIME', 120)),
    'clock_skew_seconds' => (int) env('NEWS_PREVIEW_CLOCK_SKEW_SECONDS', 60),
];

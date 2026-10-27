<?php

$previewRoot = dirname(__DIR__, 2);
require $previewRoot.'/vendor/autoload.php';
$app = require $previewRoot.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$expectedDatabase = realpath($previewRoot.'/.lmzdev/artifacts/temp/homepage-redesign-2026-10-24.sqlite');
$actualDatabase = realpath((string) config('database.connections.sqlite.database'));
if (! $expectedDatabase || config('database.default') !== 'sqlite' || $actualDatabase !== $expectedDatabase) {
    throw new RuntimeException('Preview refused: effective database is not the isolated homepage SQLite file.');
}
if (config('mail.default') !== 'array' || config('session.driver') !== 'file' || ! config('app.homepage_preview')) {
    throw new RuntimeException('Preview refused: effective mail/session/preview configuration is not isolated.');
}
echo "Preview configuration verified: isolated SQLite, file sessions, array mailer.\n";

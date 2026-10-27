param([ValidateRange(1024,65535)][int]$Port = 6666)

$ErrorActionPreference = 'Stop'
$previewRoot = (Resolve-Path -LiteralPath (Join-Path $PSScriptRoot '..\..')).Path
$previewDatabase = Join-Path $previewRoot '.lmzdev\artifacts\temp\homepage-redesign-2026-10-24.sqlite'
if (-not (Test-Path -LiteralPath $previewDatabase -PathType Leaf)) {
    throw 'Die getrennte Vorschaudatenbank fehlt. Zuerst build-homepage-preview.py ausführen.'
}
if (Get-NetTCPConnection -State Listen -LocalPort $Port -ErrorAction SilentlyContinue) {
    throw "Port $Port wird bereits verwendet."
}
$previewConfigCache = Join-Path $previewRoot ".lmzdev\artifacts\temp\homepage-preview-config-$Port.php"
if (Test-Path -LiteralPath $previewConfigCache) {
    throw 'Ein separater Preview-Konfigurationscache liegt vor. Start zur Sicherheit abgebrochen.'
}
$previewEnvironment = @{
    APP_ENV = 'local'
    APP_DEBUG = 'false'
    APP_URL = "http://127.0.0.1:$Port"
    ASSET_URL = "http://127.0.0.1:$Port"
    HOMEPAGE_PREVIEW = 'true'
    APP_CONFIG_CACHE = $previewConfigCache
    DB_CONNECTION = 'sqlite'
    DB_DATABASE = $previewDatabase
    SESSION_DRIVER = 'file'
    SESSION_COOKIE = "rc_homepage_preview_$Port"
    SESSION_DOMAIN = ''
    SESSION_SECURE_COOKIE = 'false'
    CACHE_DRIVER = 'array'
    MAIL_MAILER = 'array'
    QUEUE_CONNECTION = 'sync'
}
$previousEnvironment = @{}
foreach ($name in $previewEnvironment.Keys) {
    $previousEnvironment[$name] = [Environment]::GetEnvironmentVariable($name, 'Process')
    [Environment]::SetEnvironmentVariable($name, $previewEnvironment[$name], 'Process')
}
Push-Location -LiteralPath $previewRoot
try {
    & php (Join-Path $PSScriptRoot 'verify-homepage-preview.php')
    if ($LASTEXITCODE -ne 0) { throw 'Die isolierte Preview-Konfiguration wurde nicht bestätigt.' }
    Write-Host "Regulierungs-CHECK: lokale Laravel-Testvorschau auf http://127.0.0.1:$Port"
    Write-Host 'Getrennte SQLite-Beispieldatenbank. E-Mails bleiben im Array-Mailer. Stoppen mit Strg+C.'
    & php artisan serve --host=127.0.0.1 --port=$Port --no-reload
} finally {
    Pop-Location
    foreach ($name in $previousEnvironment.Keys) {
        [Environment]::SetEnvironmentVariable($name, $previousEnvironment[$name], 'Process')
    }
}

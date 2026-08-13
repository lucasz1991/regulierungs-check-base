<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\LogActivityJob;
use Illuminate\Support\Str;


class LogActivity
{
    public function handle($request, Closure $next)
    {
        $path = $request->path();
        $pathSlug = Str::slug($path);
        $isLivewireUpdate = $pathSlug === 'livewireupdate';
        $isSensitivePromotionScan = Str::startsWith($path, 'promotion/einloesen/');

        // Der Einmal-Token ist ein Bearer-Geheimnis. Die synchrone Promotion-
        // Auditkette protokolliert den Vorgang ohne Roh-Token; deshalb bleibt
        // das allgemeine Request-Log fuer diese URL vollstaendig aus.
        if (! $isLivewireUpdate && ! $isSensitivePromotionScan) {
            dispatch(new LogActivityJob(auth()->user(), [
                'method' => $request->method(),
                'path' => $path,
                'full_url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]));
        }
        return $next($request);
    }
}

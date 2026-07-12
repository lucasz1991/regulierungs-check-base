<?php

namespace App\Http\Middleware;

use App\Support\NewsPreviewAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class DetectNewsPreviewAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $active = NewsPreviewAccess::isActive($request);

        $request->attributes->set(NewsPreviewAccess::REQUEST_ATTRIBUTE, $active);
        View::share('newsAdminPreview', $active);
        View::share('news_admin_preview', $active);

        $response = $next($request);

        if ($active) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
            $response->headers->set('Cache-Control', 'private, no-store');
        }

        return $response;
    }
}

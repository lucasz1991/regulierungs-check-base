<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\PagebuilderProject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class PagebuilderModule extends Component
{
    public $page;
    public $position;
    public $modules;

    public function __construct($page = null, $position = 'page')    
    {
        if(!$page){
            $segments = explode('/', Request::path());
                $page = end($segments);
                if ($page === '') {
                    $page = 'start';
                } else {
                    $lastSegment = end($segments);
                    if (is_numeric($lastSegment) || strlen($lastSegment) > 25) {
                        $page = $segments[count($segments) - 2] ?? 'start';
                    }
                }
        }
        $this->page = $page;

        $this->position = $position;

        // Aktuelles Datum/Zeit für die Prüfung
        $now = Carbon::now();
        // Überprüfen, ob der user ein admin ist
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
    
        // Cache-Schlüssel generieren
        $cacheKey = "pagebuilder_modules_{$page}_{$position}_" . app()->getLocale();

        // Überprüfen, ob die Module bereits im Cache sind
        if (Cache::has($cacheKey) && Cache::get($cacheKey) !== null && $isAdmin) {
            Cache::forget($cacheKey);
        }
        
    if ($isAdmin) {
        // Admin: immer frische Daten laden, nicht cachen
        $this->modules = PagebuilderProject::where(function ($query) use ($page) {
                $query->whereJsonContains('page', $page)
                      ->orWhereJsonContains('page', 'all');
            })
            ->whereJsonContains('position', $position)
            ->whereIn('status', [0, 1, 3])
            ->where(function ($query) use ($now) {
                $query->whereNull('published_from')->orWhere('published_from', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('published_until')->orWhere('published_until', '>=', $now);
            })
            ->where(function ($query) {
                $query->where('lang', app()->getLocale())
                    ->orWhereNull('lang')
                    ->orWhere('lang', '');
            })
            ->orderBy('order_id', 'asc')
            ->get();
    } else {
        // Nicht-Admin: Cache verwenden
        $this->modules = Cache::remember($cacheKey, 60, function () use ($page, $position, $now, $isAdmin) {
            return PagebuilderProject::where(function ($query) use ($page) {
                        $query->whereJsonContains('page', $page)
                              ->orWhereJsonContains('page', 'all');
                    })
                    ->whereJsonContains('position', $position)
                    ->whereIn('status', [1, 3])
                    ->where(function ($query) use ($now) {
                        $query->whereNull('published_from')->orWhere('published_from', '<=', $now);
                    })
                    ->where(function ($query) use ($now) {
                        $query->whereNull('published_until')->orWhere('published_until', '>=', $now);
                    })
                    ->where(function ($query) {
                        $query->where('lang', app()->getLocale())
                            ->orWhereNull('lang')
                            ->orWhere('lang', '');
                    })
                    ->orderBy('order_id', 'asc')
                    ->get();
        });
    }
    }

    /**
     * Gibt die Blade-View zurück.
     */
    public function render()
    {
        return view('components.pagebuilder-module');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use App\Models\WebPage;

class PageHeader extends Component
{
    public $page;
    public bool $isWebPage = false;
    public bool $showHeader = false;
    public $title;
    public $icon;
    public $header_image;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
$segments = explode('/', trim(Request::path(), '/'));
$segmentCount = count($segments);

// Fallback-Slug, falls keine Seite gefunden wird
$currentSlug = 'start';

// Von hinten durchgehen und auf passende WebPage prüfen
for ($i = $segmentCount - 1; $i >= 0; $i--) {
    $slugToTest = $segments[$i];

    // Optional: sehr lange Slugs oder rein numerische ausschließen
    if (strlen($slugToTest) > 50 || is_numeric($slugToTest)) {
        continue;
    }

    $webPage = WebPage::where('slug', $slugToTest)->first();
    if ($webPage) {
        $currentSlug = $slugToTest;
        break;
    }
}

// Falls du mit $webPage weiterarbeiten willst, kannst du sicherstellen:
$webPage = WebPage::where('slug', $currentSlug)->first();
        $this->isWebPage = $webPage !== null;
        if ($webPage) {
            // Falls eine WebPage existiert, verwende deren Daten, ansonsten Standardwerte
            $this->showHeader = $webPage->settings['showHeader'];
            $this->title = $webPage->title;
            $this->icon = $webPage->icon;
            $this->header_image = $webPage->header_image;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-header');
    }
}

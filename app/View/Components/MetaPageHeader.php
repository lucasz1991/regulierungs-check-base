<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use App\Models\WebPage;

class MetaPageHeader extends Component
{
    public bool $isWebPage = false;
    public ?string $title = null;
    public ?string $metaTitle = null;
    public ?string $metaDescription = null;
    public ?string $metaKeywords = null;
    public ?string $canonicalUrl = null;
    public ?string $robotsMeta = null;
    public ?string $ogTitle = null;
    public ?string $ogDescription = null;
    public ?string $ogImage = null;
    public ?string $customCss = null;
    public ?string $customJs = null;
    public ?string $customMeta = null;

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

        // Prüfen, ob eine passende WebPage existiert
        $this->isWebPage = $webPage !== null;
        if ($webPage) {
            // Falls eine WebPage existiert, verwende deren Daten, ansonsten Standardwerte
            $this->title = $webPage->title ?? config('app.name');
            $this->metaTitle = $webPage->meta_title ?? $this->title;
            $this->metaDescription = $webPage->meta_description ?? '';
            $this->metaKeywords = $webPage->meta_keywords ?? '';
            $this->canonicalUrl = $webPage->canonical_url ?? url()->current();
            $this->robotsMeta = $webPage->robots_meta ?? 'noindex, nofollow';
            $this->ogTitle = $webPage->og_title ?? $this->metaTitle;
            $this->ogDescription = $webPage->og_description ?? $this->metaDescription;
            $this->ogImage = $webPage->og_image;
            $this->customCss = $webPage->custom_css ?? '';
            $this->customJs = $webPage->custom_js ?? '';
        }
    }

    public function render()
    {
        return view('components.meta-page-header');
    }
}

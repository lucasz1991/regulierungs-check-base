    @if ($isWebPage && $showHeader)
        <header class="app-page-header{{ request()->routeIs('aboutus', 'howto', 'guidance', 'contact', 'faqs', 'abos', 'sitemap', 'imprint', 'terms', 'privacypolicy', 'blog.index', 'post.show', 'news.index', 'news.show', 'login', 'register', 'password.*') ? ' is-compact' : '' }}" >
            
            <div class="app-shell app-page-header-inner">
                <x-back-button />
                <h1 class="app-page-title">
                    {{ $title }}
                    @if (!empty($safeIcon))
                        <span class="pageheader-icon" aria-hidden="true">
                            {!! $safeIcon !!}
                        </span>
                    @endif
                </h1>
            </div>
        </header>
    @endif

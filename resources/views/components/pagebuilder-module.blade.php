@php
    $moduleContext = $homepageModuleContext ?? null;
    $isHomepageModule = in_array($moduleContext, ['content', 'footer'], true);
    $normalizeModuleHeadings = $isHomepageModule || ($normalizeHeadings ?? false) || $position === 'footer';
    $hasModules = $modules && count($modules) > 0;
    $hasDesignContent = $hasModules && collect($modules)->contains(function ($module) {
        $html = preg_replace('~<(script|style|template)\b[^>]*>[\s\S]*?</\1\s*>~i', '', (string) $module->cleaned_html) ?? '';
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_match('~[^\s\x{00A0}]~u', $text)
            || preg_match('~<(?:img|svg|video|audio|iframe|form|input|button|canvas|object|embed|hr)\b~i', $html)
            || trim((string) $module->js) !== '';
    });
@endphp
<div class="{{ $position }}{{ $isHomepageModule ? ' homepage-cms homepage-cms-' . $homepageModuleContext : '' }}{{ $hasDesignContent && !request()->routeIs('home') ? ' app-cms app-cms-' . $position : '' }}" wire:ignore>
    @if($hasModules)
        @foreach($modules as $module)
        @php
            $moduleHtml = (string) $module->cleaned_html;
            if ($normalizeModuleHeadings) {
                // Keep attributes and raw-text content intact; only normalize tags and visible footer text.
                $moduleHtml = preg_replace_callback(
                    '~<!--[\s\S]*?-->|<(script|style|textarea|title)\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>[\s\S]*?</\1\s*>|</?[a-z][a-z0-9:-]*(?:[^>"\']|"[^"]*"|\'[^\']*\')*>|[^<]+~i',
                    function ($token) use ($moduleContext) {
                        if (str_starts_with($token[0], '<')) {
                            return preg_replace('~^(</?)h1(?=[\s/>])~i', '$1h2', $token[0]);
                        }

                        if ($moduleContext !== 'footer') {
                            return $token[0];
                        }

                        return preg_replace_callback(
                            '~Regulierungs-CHECK(\x{00A0}|&nbsp;)?~u',
                            fn ($brand) => '<span class="newsletter-brand">Regulierungs-CHECK</span>' . (isset($brand[1]) ? ' ' : ''),
                            $token[0],
                        ) ?? $token[0];
                    },
                    $moduleHtml,
                ) ?? $moduleHtml;
            }
        @endphp
        <div id="module-{{ $module->id }}" class="module-{{ $module->id }}">
            {!! $moduleHtml !!}
            <style>{!! $module->css !!}</style>
            
            <script>{!! $module->js !!}</script>
            
        </div>
        @endforeach
    @endif
</div>

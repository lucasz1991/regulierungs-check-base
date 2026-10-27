@php
    $isHomepageModule = in_array($homepageModuleContext ?? null, ['content', 'footer'], true);
@endphp
<div class="{{ $position }}{{ $isHomepageModule ? ' homepage-cms homepage-cms-' . $homepageModuleContext : '' }}" wire:ignore>
    @if($modules && count($modules) > 0)
        @foreach($modules as $module)
        @php
            $moduleHtml = (string) $module->cleaned_html;
            if ($isHomepageModule) {
                // Keep attributes and raw-text content intact; only normalize tags and visible footer text.
                $moduleHtml = preg_replace_callback(
                    '~<!--[\s\S]*?-->|<(script|style|textarea|title)\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>[\s\S]*?</\1\s*>|</?[a-z][a-z0-9:-]*(?:[^>"\']|"[^"]*"|\'[^\']*\')*>|[^<]+~i',
                    function ($token) use ($homepageModuleContext) {
                        if (str_starts_with($token[0], '<')) {
                            return preg_replace('~^(</?)h1(?=[\s/>])~i', '$1h2', $token[0]);
                        }

                        if ($homepageModuleContext !== 'footer') {
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

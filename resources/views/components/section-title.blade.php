<div class="app-section-title border-b border-[var(--line)] px-5 py-6 sm:px-7">
    <div class="">
        <h3 class="text-lg font-semibold text-[var(--ink)]">{{ $title }}</h3>

        <div class="app-muted mt-2 text-sm leading-7">
            {{ $description }}
        </div>
    </div>

    <div class="">
        {{ $aside ?? '' }}
    </div>
</div>

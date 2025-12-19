<div {{ $attributes->merge(['class' => 'rounded-xl overflow-hidden']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="">
        <div class="px-4 py-5 bg-white/90 sm:p-6">
            {{ $content }}
        </div>
    </div>
</div>

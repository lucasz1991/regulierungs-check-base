@props(['submit'])

<div {{ $attributes->merge(['class' => ' rounded-xl overflow-hidden ']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="">
        <form wire:submit="{{ $submit }}">
            <div class="px-4 py-5 bg-white/90  sm:p-6 ">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 bg-white text-end sm:px-6 ">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>

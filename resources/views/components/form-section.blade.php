@props(['submit'])

<div {{ $attributes->merge(['class' => 'app-form-section rounded-[18px] border border-[var(--line)] bg-[var(--surface)] overflow-hidden']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="">
        <form wire:submit="{{ $submit }}">
            <div class="px-5 py-6 sm:p-7">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-[var(--line)] bg-[var(--surface-soft)] px-5 py-4 text-end sm:px-7">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>

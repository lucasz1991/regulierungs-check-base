<div {{ $attributes->merge(['class' => 'app-form-section rounded-[18px] border border-[var(--line)] bg-[var(--surface)] overflow-hidden']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="">
        <div class="px-5 py-6 sm:p-7">
            {{ $content }}
        </div>
    </div>
</div>

{{-- Component ui.forms.toggle-multi  --}}
@props([
    'id'    => 'toggle-' . \Illuminate\Support\Str::random(6),
    'label' => null,
    'model' => null,   // z.B. "regulationDetails" (ARRAY)
    'value' => null,   // z.B. "Innerhalb 1 Woche"
    'icon'  => 'fa-check-circle', // FontAwesome 5 (solid)
])

<label for="{{ $id }}" class="flex items-center justify-between gap-4 rounded-2xl border border-white/20 bg-white/90 px-2 py-2 shadow-sm backdrop-blur cursor-pointer select-none">
    <div class="flex items-center gap-3 min-w-0">
        <div class="mt-0.5 w-12 h-12  flex items-center justify-center shrink-0">
            <i class="fal {{ $icon }} fa-2x text-primary"></i>
        </div>

        <div class="grow text-left">
            <div class="text-sm font-semibold text-primary">
                {{ $label }}
            </div>
        </div>
    </div>

    {{-- Checkbox -> Multi Select --}}
    <input
        id="{{ $id }}"
        type="checkbox"
        value="{{ $value }}"
        @if($model) wire:model.live="{{ $model }}" @endif
        class="sr-only peer"
    />

    {{-- Slider --}}
    <div class="relative w-9 h-5 min-w-9 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer
                dark:bg-gray-700
                peer-checked:after:translate-x-full
                rtl:peer-checked:after:-translate-x-full
                peer-checked:after:border-white
                after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                after:bg-white after:border-gray-300 after:border after:rounded-full
                after:h-4 after:w-4 after:transition-all dark:border-gray-600
                peer-checked:bg-blue-600">
    </div>
</label>

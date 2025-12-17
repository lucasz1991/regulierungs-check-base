<!-- Component buttons furtherbutton  -->
@props([
    'action' => 'nextStep',
])

<button
    type="button"
    wire:click="{{ $action }}"
    wire:loading.attr="disabled"
    {!! $attributes->merge([
        'class' => '
            group/furtherbutton
            transition-all duration-150
            inline-flex items-center justify-center
            p-4 aspect-square rounded-full
            bg-rcgold-light text-green-900
            border border-rcgold
            hover:bg-rcgold
            focus:ring-4 focus:ring-green-100
        '
    ]) !!}
    x-data="{ isClicked: false }"
    @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
    style="transform:scale(1);"
    :style="isClicked ? 'transform:scale(0.9);' : ''"
>

    <svg
        class="
            h-6 aspect-square
            transition-colors duration-150
            text-rcgold
            group-hover/furtherbutton:text-rcgold-dark
        "
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 648.63399 645.8089"
    >
        <polygon
            fill="currentColor"
            points="391.537 0 0 0 257.097 322.904 0 645.809 391.537 645.809 648.634 322.904 391.537 0"
        />
    </svg>
</button>
<!-- Component buttons furtherbutton End -->
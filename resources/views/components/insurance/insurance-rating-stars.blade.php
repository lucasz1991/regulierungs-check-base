@php  
    $scoreZeroToFive = $score > 0 ? $score * 5 : 0;
    $size = $size ?? 'xs'; 
    $sizeClasses = [
        'xs' => 'w-5 h-5',
        'sm' => 'w-5 h-5',
        'md' => 'w-6 h-6',
        'lg' => 'w-7 h-7',
        'xl' => 'w-8 h-8',
    ];
    $fontSizeClasses = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg',
    ];
@endphp
 
<div class="flex items-center justify-center">
    @for ($i = 0; $i < 5; $i++)
        @php
            $starPercentage = min(max($scoreZeroToFive - $i, 0), 1) * 100;
            $uniqueId = uniqid('star_'.$i.'_');
        @endphp
        <div class="{{ $sizeClasses[$size] ?? 'w-5 h-5' }} relative">
            <svg class="w-full h-full" viewBox="0 0 20 20">
                <defs>
                    <linearGradient id="{{ $uniqueId }}">
                        <stop offset="{{ $starPercentage }}%" stop-color="#fbbf24"/>
                        <stop offset="{{ $starPercentage }}%" stop-color="#d1d5db"/>
                    </linearGradient>
                </defs>
                <path fill="url(#{{ $uniqueId }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
            </svg>
        </div>
    @endfor
    <div class="{{ $fontSizeClasses[$size] ?? '' }} ml-2 @if($size != 'xs') min-w-12 text-gray-400 @else text-white @endif font-medium">{{ number_format($scoreZeroToFive, 1) }} @if($size != 'xs') / 5 @endif</div>
</div>



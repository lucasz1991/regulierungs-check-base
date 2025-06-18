<div  class="flex  items-stretch gap-4 mb-4">
    <div class="shrink-0 transition-all duration-200 flex "
        :class="hover || showInfos ? 'opacity-100' : ''">
        @if ($insurance->logo)
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                alt=""
                class=" h-8 mx-auto object-contain rounded">
        @else
            <div class=" w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
            </div>
        @endif
    </div>
    <div class="grow  min-w-0  transition-all duration-200"
        :class="hover || showInfos ? 'opacity-100' : 'opacity-90'">
        <div >
            <h2 class="text-lg break-words  truncate text-ellipsis pr-2">
                {{  $insurance->name }}
            </h2>
        </div>
    </div>
</div>
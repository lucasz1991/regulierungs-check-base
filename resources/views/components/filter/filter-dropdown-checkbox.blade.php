<div x-data="dropdownFilter(@json($options->pluck('id')))" class="relative inline-block w-full max-w-md text-left">
<!-- Trigger -->
    <div @click="toggle" class="cursor-pointer rounded-md bg-white border border-blue-200 px-4 py-2.5 text-sm text-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-2">
    <template x-if="selected.length && selected.length < all.length">
        <span class="inline-flex items-center gap-2">
            <span class="inline-flex items-center justify-center text-xs font-semibold bg-secondary/80  text-white rounded-full min-w-6 h-6 px-1">
                <span x-text="selected.length"></span>
            </span>
            <span>ausgewählt</span>
        </span>
    </template>
            <template x-if="!selected.length || isAllSelected()">
                <span>Alle ausgewählt</span>
            </template>
        </div>
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <input type="hidden" 
       x-model="selected" 
       :value="JSON.stringify(selected)" 
       {!! $attributes->merge(['class' => '']) !!}>

    <!-- Dropdown -->
    <div x-show="open" @click.outside="close" x-transition
         class="absolute z-10 mt-2 max-xl:right-0 xl:left-0 w-max max-w-md bg-white border border-gray-300 rounded-xl shadow-xl overflow-hidden">
        <div class="">
            <!-- Toggle-All-Bereich -->
            <div class="px-4 py-2 border-b bg-gray-200">
                <template x-if="isAllSelected()">
                    <div @click="toggleAll"
                         class="flex items-center space-x-2   text-red-600 cursor-pointer hover:underline">
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Alle entfernen</span>
                    </div>
                </template>

                <template x-if="!isAllSelected()">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" @change="toggleAll" :checked="false" class="form-checkbox text-secondary rounded-full">
                        <span>Alle auswählen</span>
                    </label>
                </template>
            </div>
            <template x-if="all.length > 10">
                <div class="px-4 py-2  border-b border-gray-300">
                    <input type="text"
                        x-model="search"
                        placeholder="Suche..."
                        class="w-full border border-blue-200 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                    >
                </div>
            </template>
    
            <!-- Optionen -->
            <div class="px-4 py-2 bg-primary-50/50  divide-y divide-gray-200 max-h-[40dvh] overflow-y-auto scroll-container scroll-smooth" >
                @foreach($options as $option)
                    <label class="flex items-start space-x-2 py-2" x-show="!search || '{{ strtolower($option->name) }}'.includes(search.toLowerCase())">
                        <input type="checkbox" :value="{{ $option->id }}" x-model="selected" class="form-checkbox text-secondary rounded-full mt-1">
                        <span>{{ $option->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function dropdownFilter(optionIds) {
            return {
                open: false,
                selected: [],
                all: optionIds,
                search: '',
                toggle() {
                    this.open = !this.open;
                },
                close() {
                    this.open = false;
                },
                isAllSelected() {
                    return this.selected.length === this.all.length;
                },
                toggleAll() {
                    this.selected = this.isAllSelected() ? [] : [...this.all];
                }
            }
        }
    </script>
</div>

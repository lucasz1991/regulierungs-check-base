<div 
    x-data="{
        showFilters: false,
        init() {
            this.$watch('showFilters', value => {
                if (value && this.$refs.filterPanel) {
                    this.$nextTick(() => {
                        this.$refs.filterPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                }
            });
        }
    }"
    x-init="init()"
    {{ $attributes->merge(['class' => '']) }}
>
    <div class="container mx-auto p-4 pt-10 pb-8">
        <div class="mb-4 max-xl:flex max-xl:justify-end">
            <button @click="showFilters = !showFilters" class="text-sm text-blue-600 hover:underline p-2 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                <svg :class="{ 'rotate-180': showFilters }"
                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transform transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Filter</span>                            
            </button>
        </div>
        <div class="xl:grid xl:grid-cols-12 xl:gap-6">
            <div x-show="showFilters"  x-cloak class="filter-sidebar xl:col-span-2 max-xl:absolute max-xl:right-4 z-40">
                <div x-show="showFilters" x-transition class="max-xl:fixed xl:hidden inset-0 transform transition-all" x-on:click="showFilters = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div x-show="showFilters" x-transition x-ref="filterPanel" class="relative flex  w-full max-w-[20rem] flex-col rounded-xl bg-white bg-clip-border border border-gray-300 p-2 text-gray-700 shadow-xl shadow-gray-900/5  z-30">
                    {{ $filters }}
                </div>
            </div>
            <div class="filter-sidebar" :class="showFilters ? 'xl:col-span-10' : 'xl:col-span-12'" x-transition>
                {{ $listContent }}
            </div>
        </div>
    </div>
</div>





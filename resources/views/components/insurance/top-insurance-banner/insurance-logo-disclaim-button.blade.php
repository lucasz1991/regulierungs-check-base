<div x-data="{ show: false }" class="relative">
    <!-- Info-Icon -->
    <button
        @mouseenter="show = true"
        @mouseleave="show = false"
        @click.away="show = false"
        @click.prevent="show = true"
        x-ref="anchor"
        class="text-primary hover:text-primary-dark focus:outline-none p-0.5 bg-white rounded-full flex items-center" 
        aria-label="Hinweis zur Logo-Nutzung"
        type="button"
    >
        <svg class="w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.75 20.5 2.5 16.25 2.5 11S6.75 1.5 12 1.5 21.5 5.75 21.5 11 17.25 20.5 12 20.5z" />
        </svg>
    </button>
    <!-- Hinweis-Box -->
     <template x-teleport="#disclaimpopup" >
        <div
            x-show="show"
            x-transition
            x-anchor.offset.10="$refs.anchor"
            x-cloak
            class="w-64 z-50 bg-gray-100 text-sm text-gray-800 rounded shadow-lg p-3"
        >
            <p class="leading-snug">
            Das Logo wird ausschließlich zur Identifikation verwendet. Es besteht keine geschäftliche Verbindung. Markenrechte liegen beim jeweiligen Versicherer.
            </p>
        </div>
    </template>
</div>
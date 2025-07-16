                    <div x-data="{ show: false }" class="relative">
                        <!-- Info-Icon -->
                        <button
                            @mouseenter="show = true"
                            @mouseleave="show = false"
                            @click.away="show = false"
                            x-on:click="show = !show"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none -mt-4"
                            aria-label="Hinweis zur Logo-Nutzung"
                            type="button"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.75 20.5 2.5 16.25 2.5 11S6.75 1.5 12 1.5 21.5 5.75 21.5 11 17.25 20.5 12 20.5z" />
                            </svg>
                        </button>
                        <!-- Hinweis-Box -->
                        <div
                            x-show="show"
                            x-transition
                            class="absolute z-50 top-full left-0 mt-2 w-64 bg-white text-sm text-gray-800 rounded shadow-lg p-3"
                        >
                            <p class="leading-snug">
                            Das Logo wird ausschließlich zur Identifikation verwendet. Es besteht keine geschäftliche Verbindung. Markenrechte liegen beim jeweiligen Versicherer.
                            </p>
                        </div>
                    </div>
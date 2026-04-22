<div class="container mx-auto px-2 md:px-4">
    @php
        $insurancesUrl = route('insurances', $selectedInsuranceTypeId ? ['types' => (string) $selectedInsuranceTypeId] : []);
    @endphp

    <div class="mb-3 md:mb-4 overflow-x-auto">
        <div class="text-xs md:text-sm text-white font-semibold mb-2">
            Versicherungs Kategorie filtern
        </div>
        <div class="flex items-stretch gap-2 min-w-max pb-1">
            <button
                type="button"
                wire:click="clearInsuranceTypeFilter"
                @class([
                    'inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-semibold transition',
                    'bg-rcgold-light border-rcgold-light text-primary' => is_null($selectedInsuranceTypeId),
                    'bg-white/90 border-gray-200 text-gray-700 hover:bg-white' => !is_null($selectedInsuranceTypeId),
                ])
            >
                <i class="fal fa-layer-group"></i>
                <span>Alle</span>
            </button>

            @foreach ($types as $type)
                <button
                    type="button"
                    wire:click="selectInsuranceType({{ $type->id }})"
                    @class([
                        'inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-semibold transition',
                        'bg-rcgold-light border-rcgold-light text-primary' => (int) $selectedInsuranceTypeId === (int) $type->id,
                        'bg-white/90 border-gray-200 text-gray-700 hover:bg-white' => (int) $selectedInsuranceTypeId !== (int) $type->id,
                    ])
                >
                    @if (!empty($type->icon_svg))
                        <span class="[&_svg]:w-4 [&_svg]:h-4">
                            @if ($type->icon_type === 'svg' && $type->icon_svg)
                                {!! $type->icon_svg !!}
                            @elseif ($type->icon_type === 'fontawesome')
                                <i class="{!! $type->icon_svg !!} fa-sm"></i>
                            @endif
                        </span>
                    @else
                        <i class="fal fa-shield-alt"></i>
                    @endif

                    <span class="whitespace-nowrap">{{ $type->name }}</span>
                </button>
            @endforeach
        </div>
    </div>

    @if ($insurances->isNotEmpty())
        <div class="my-4" wire:key="top-insurance-by-type-swiper-{{ $selectedInsuranceTypeId ?? 'all' }}">
            <div x-data="{
                    swiper: null,
                    initSwiper() {
                        const boot = () => {
                            if (typeof Swiper === 'undefined') {
                                requestAnimationFrame(boot);
                                return;
                            }

                            if (this.swiper) {
                                this.swiper.destroy(true, true);
                            }

                            this.swiper = new Swiper(this.$refs.topSwiper, {
                                slidesPerView: 'auto',
                                spaceBetween: 0,
                                slidesOffsetBefore: 0,
                                slidesOffsetAfter: 0,
                                speed: 500,
                                loop: false,
                                freeMode: false,
                                autoHeight: false,
                                pagination: {
                                    el: '.swiper-pagination-top',
                                    clickable: true,
                                },
                                breakpoints: {
                                    640: { slidesPerView: 'auto' },
                                    1024: { slidesPerView: 'auto' },
                                }
                            });
                        };

                        boot();
                    }
                }"
                x-init="initSwiper()"
                class="relative"
                wire:ignore
            >
                <div class="swiper h-full" x-ref="topSwiper">
                    <div class="swiper-wrapper h-full">
                        @foreach ($insurances as $insurance)
                            <div class="swiper-slide w-36 pr-4 h-full" wire:key="top-insurance-filtered-{{ $selectedInsuranceTypeId ?? 'all' }}-{{ $insurance->id }}">
                                <x-insurance.insurance-card-startswiper
                                    :insurance="$insurance"
                                    :isSubTypeFilter="!empty($selectedSubtypeIds)"
                                    :subTypeFilterSubType="null"
                                    :subTypeFilterSubTypeIds="$selectedSubtypeIds"
                                    :selectedInsuranceTypeId="$selectedInsuranceTypeId"
                                />
                            </div>
                        @endforeach

                        <div class="swiper-slide w-36 pr-4 h-full">
                            <a href="{{ $insurancesUrl }}"
                               class="block h-full hover:shadow-lg cursor-pointer overflow-hidden rounded-xl shadow">
                                <div class="bg-white px-2 pt-9 pb-2 relative transition-shadow duration-300 flex flex-col justify-center items-center h-full">
                                    <div class="w-12 h-12 rounded-full bg-secondary-light ring-2 ring-offset-2 ring-secondary-light transition-all duration-200 flex items-center justify-center mt-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                    <div class="transition-all duration-200 text-center h-full flex items-end justify-center" style="margin-top:3px">
                                        <div class="h-10 flex items-end justify-center text-xs font-medium text-gray-600">
                                            weitere Anbieter vergleichen
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="disclaimpopup"></div>
            </div>
        </div>
    @else
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 text-yellow-800 px-4 py-3 my-4 text-sm">
            Fuer diese Versicherungsart liegen aktuell noch keine veroeffentlichten Bewertungen vor.
        </div>
    @endif
</div>

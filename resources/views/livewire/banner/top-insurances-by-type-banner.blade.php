<div class="bg-gray-50">
    <div class="container mx-auto px-4 py-5 md:px-6 md:py-10">
        @php
            $insurancesUrl = route('insurances', $selectedInsuranceTypeId ? ['types' => (string) $selectedInsuranceTypeId] : []);
            $selectedType = $selectedInsuranceTypeId ? $types->firstWhere('id', $selectedInsuranceTypeId) : null;
            $topInsurerTitle = $selectedType?->name
                ? 'Top Versicherer - ' . $selectedType->name
                : 'Top Versicherer';
            $displayTypes = $types->take(4);
        @endphp

        <div class="mx-auto">
            <div class="mb-4 text-center md:mb-8">
                <h2 class="text-lg font-semibold leading-[1.02] tracking-[-0.02em] text-[#12324f] md:text-lg">
                    Welche Versicherung
                </h2>
                <div class="mt-1.5 flex items-center justify-center gap-3 md:mt-2 md:gap-6">
                    <span class="h-px w-10 bg-slate-200 md:w-32"></span>
                    <p class="text-md font-medium leading-tight text-slate-800 md:text-md">
                        möchtest du vergleichen?
                    </p>
                    <span class="h-px w-10 bg-slate-200 md:w-32"></span>
                </div>
            </div>

            <div class="mb-3 grid grid-cols-2 gap-2.5 md:mb-10 md:grid-cols-3 md:gap-3">
                @foreach ($displayTypes as $type)
                    <button
                        type="button"
                        wire:click="selectInsuranceType({{ $type->id }})"
                        @class([
                            'inline-flex min-h-[3rem] items-center gap-1.5 rounded-xl border px-2 py-1 text-left text-[0.82rem] font-medium shadow-[0_6px_18px_rgba(15,23,42,0.10)] transition-all md:min-h-[3.6rem] md:gap-2 md:px-3.5 md:py-2.5 md:text-[0.9rem]',
                            'border-[#bfe0e6] bg-gradient-to-br from-[#e6f6f9] to-[#f8fcfd] text-[#12324f] shadow-[0_8px_22px_rgba(15,23,42,0.12)]' => (int) $selectedInsuranceTypeId === (int) $type->id,
                            'border-slate-200 bg-white text-slate-800 hover:border-[#d4e7eb] hover:bg-slate-50' => (int) $selectedInsuranceTypeId !== (int) $type->id,
                        ])
                    >
                        <span @class([
                            'shrink-0 text-[1rem] leading-none md:text-[1.2rem]',
                            'text-[#1f6f8b]' => (int) $selectedInsuranceTypeId === (int) $type->id,
                            'text-slate-500' => (int) $selectedInsuranceTypeId !== (int) $type->id,
                        ])>
                            @if (!empty($type->icon_svg))
                                <span class="block h-4 w-4 [&_svg]:h-4 [&_svg]:w-4 md:h-5 md:w-5 md:[&_svg]:h-5 md:[&_svg]:w-5">
                                    @if ($type->icon_type === 'svg' && $type->icon_svg)
                                        {!! $type->icon_svg !!}
                                    @elseif ($type->icon_type === 'fontawesome')
                                        <i class="{!! $type->icon_svg !!}"></i>
                                    @endif
                                </span>
                            @else
                                <i class="fal fa-shield-alt"></i>
                            @endif
                        </span>

                        <span class="min-w-0 truncate text-[0.8rem] leading-tight md:text-[0.9rem]">{{ $type->name }}</span>
                    </button>
                @endforeach
            </div>
            <div class="flex justify-center mb-2">
                <a href="{{ $insurancesUrl }}"
                    class="inline-flex items-center gap-1.5 rounded-full bg-[#0f6b86] px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.04em] text-white shadow-[0_8px_20px_rgba(15,107,134,0.28)] transition hover:bg-[#0c5870]"
                >
                    Alle zeigen
                    <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            @if ($insurances->isNotEmpty())
                <div class="mb-4 flex items-center gap-4 md:mb-5 md:gap-6">
                    <div class="min-w-0 shrink text-[1.55rem] font-medium tracking-[-0.02em] text-[#1a2d42] md:text-[2rem]">
                        @if ($selectedType?->name)
                            <h3 class="flex min-w-0 items-baseline gap-2">
                                <span class="min-w-0 truncate font-semibold">{{ $selectedType->name }}</span>
                            </h3>
                        @else
                            <h3>Top Versicherer</h3>
                        @endif
                    </div>
                    <span class="h-px flex-1 bg-slate-200"></span>
                </div>

                <div class="my-4" wire:key="top-insurance-by-type-swiper-{{ $selectedInsuranceTypeId ?? 'all' }}">
                    <div
                        x-data="{
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
                                        slidesOffsetBefore: 10,
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
                            <div class="swiper-wrapper h-full py-2">
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
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    Fuer diese Versicherungsart liegen aktuell noch keine veroeffentlichten Bewertungen vor.
                </div>
            @endif
        </div>
    </div>
</div>

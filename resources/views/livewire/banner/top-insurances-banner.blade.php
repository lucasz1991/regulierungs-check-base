<div>
    {{-- TOP 5 SWIPER --}}
    <div class="my-4">
        <div x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.topSwiper, {
                        slidesPerView: 'auto',
                        spaceBetween: 0,
                        slidesOffsetBefore: 20,
                        slidesOffsetAfter: 20,
                        speed: 500,
                        loop: false,
                        freeMode: true,
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
                }
            }"
            x-init="initSwiper()"
            class="relative"
            wire:ignore
        >
            <div class="swiper overflow-y-visible" x-ref="topSwiper">
                <div class="swiper-wrapper">
                    @foreach ($insurances as $insurance)
                        <div class="swiper-slide w-36 pr-4" wire:key="top-insurance-{{ $insurance->id }}">
                            <x-insurance.insurance-card-startswiper :insurance="$insurance" :isSubTypeFilter="$isSubTypeFilter" :subTypeFilterSubType="$subTypeFilterSubType" />
                        </div>
                    @endforeach
                    <div class="swiper-slide w-36 pr-4 h-full">
                          <a href="/insurances" class="block  hover:shadow-lg  cursor-pointer  overflow-hidden   rounded shadow h-full" x-data="{ hover: false }" @click.away="showInfos = false" x-cloak>
                            <div class="bg-white px-2 py-2 relative transition-shadow duration-300 flex flex-col justify-center items-center h-full"
                                x-on:mouseenter="hover = true"
                                x-on:mouseleave="hover = false"
                                >
                                <div class=" transition-all duration-200 text-center">
                                    <div class="h-8 w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm bg-gray-100 text-gray-600 border-gray-300">
                                        weitere Anbieter vergleichen
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto">
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
            <div class="swiper   h-full" x-ref="topSwiper">
                <div class="swiper-wrapper  h-full">
                    @foreach ($insurances as $insurance)
                        <div class="swiper-slide w-36 pr-4  h-full" wire:key="top-insurance-{{ $insurance->id }}">
                            <x-insurance.insurance-card-startswiper :insurance="$insurance" :isSubTypeFilter="$isSubTypeFilter" :subTypeFilterSubType="$subTypeFilterSubType" />
                        </div>
                    @endforeach
                    <div class="swiper-slide w-36 pr-4 h-full">
                        <a href="/insurances"
                        class="block h-full hover:shadow-lg cursor-pointer overflow-hidden rounded shadow"
                        x-data="{ hover: false }"
                        x-cloak>
                            <div class="bg-white px-2 pt-9 pb-2 relative transition-shadow duration-300 flex flex-col justify-center items-center h-full"
                                x-on:mouseenter="hover = true"
                                x-on:mouseleave="hover = false">
                                <div class="w-12 h-12 rounded-full bg-secondary-light ring-2 ring-offset-2 ring-secondary-light transition-all duration-200 flex items-center justify-center  mt-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <div class="transition-all duration-200 text-center h-full flex items-end justify-center" style="margin-top:3px">
                                    <div class="h-10  flex items-end justify-center text-xs   font-medium  text-gray-600 ">
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
</div>

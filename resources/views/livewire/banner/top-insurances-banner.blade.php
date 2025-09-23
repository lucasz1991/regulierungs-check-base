<div>
    {{-- TOP 5 SWIPER --}}
    <div class="my-4">
        <div x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.topSwiper, {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        slidesOffsetBefore: 20,
                        slidesOffsetAfter: 20,
                        speed: 2500,
                        loop: false,
                        freeMode: true,
                        pagination: {
                            el: '.swiper-pagination-top',
                            clickable: true,
                        },
                        breakpoints: {
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 },
                        }
                    });
                }
            }"
            x-init="initSwiper()"
            class="relative"
            wire:ignore
        >
            <div class="swiper" x-ref="topSwiper">
                <div class="swiper-wrapper">
                    @foreach ($insurances as $insurance)
                        <div class="swiper-slide">
                            <x-insurance.insurance-card-startswiper :insurance="$insurance" :isSubTypeFilter="$isSubTypeFilter" :subTypeFilterSubType="$subTypeFilterSubType" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

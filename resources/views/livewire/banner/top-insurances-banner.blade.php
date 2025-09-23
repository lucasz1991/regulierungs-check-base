<div>
    {{-- TOP 5 SWIPER --}}
    <div class="my-4">
        <div x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.topSwiper, {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        speed: 2500,
                        loop: false,
                        pagination: {
                            el: '.swiper-pagination-top',
                            clickable: true,
                        },
                        breakpoints: {
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 },
                        }
                    });
                },
                stopSwiper() {
                    this.swiper.autoplay.stop();
                },
                playSwiper() {
                    this.swiper.autoplay.start();
                }
            }"
            x-init="initSwiper()"
            x-on:click="stopSwiper()"
            x-on:click.away="playSwiper()"
            x-on:touchstart="stopSwiper()"
            x-on:touchend="playSwiper()"
            class="relative"
            wire:ignore
        >
            <div class="swiper" x-ref="topSwiper">
                <div class="swiper-wrapper">
                    @foreach ($insurances as $insurance)
                        <div class="swiper-slide">
                            <x-insurance.insurance-card :insurance="$insurance" :isSubTypeFilter="$isSubTypeFilter" :subTypeFilterSubType="$subTypeFilterSubType" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    {{-- TOP 5 SWIPER --}}
    <div class="my-12">
        <h2 class="text-xl font-bold mb-4 text-green-900">5 Versicherungen mit den besten Bewertungen</h2>
        <div x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.topSwiper, {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        autoplay: {
                            delay: 1300,
                        },
                        speed: 2500,
                        loop: true,
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
                    @foreach ($top5 as $insurance)
                        <div class="swiper-slide">
                            <x-insurance.insurance-card :insurance="$insurance" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

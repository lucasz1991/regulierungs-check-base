<div class="relative bg-gray-50 overflow-hidden pt-12">
    <div x-data="{
            swiper: null,
            initSwiper() {
                this.swiper = new Swiper(this.$refs.swiper, {
                    autoplay: {
                        delay: 0,
                    },
                    disableOnInteraction: true,
                    speed: 5000,
                    loop: true,
                    centeredSlides: true,
                    slidesPerView: '1',
                    spaceBetween: 20,
                    disableOnMobile: true,
                    breakpoints: {
                        1000: {
                            slidesPerView: 3,
                        },
                        1200: {
                            slidesPerView: 4,
                        },
                        1400: {
                            slidesPerView: 5,
                        },
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                });
                this.swiper.slideNext();
            },
            stopSwiper() {
                this.swiper.autoplay.stop();
            },
            startSwiper() {
                this.swiper?.autoplay?.start();
            }
        }"
        x-init="initSwiper() "
        x-on:click="stopSwiper(); setTimeout(() => startSwiper(), 5000)"

        class=" relative w-full"
        wire:ignore
    >
        {{-- Navigation links/rechts außerhalb --}}
        
        <div class="swiper w-full" x-ref="swiper" >
            <div class="swiper-wrapper pointer-events-none !ease-linear">
                @foreach ($claimRatings as $claimRating)
                    <div class="swiper-slide h-full px-4">
                        <x-claim-rating.claim-rating-card :rating="$claimRating" />
                    </div>
                @endforeach
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

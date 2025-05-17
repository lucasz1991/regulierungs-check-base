<div class="relative bg-[#cee5ef] overflow-hidden py-4">
    <div x-data="{
            swiper: null,
            initSwiper() {
                this.swiperclaimRatings = new Swiper(this.$refs.swiperclaimRatings, {
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
                    
                });
                this.swiperclaimRatings.slideNext();
            },
            stopSwiper() {
                this.swiperclaimRatings.autoplay.stop();
            },
            startSwiper() {
                this.swiperclaimRatings?.autoplay?.start();
            }
        }"
        x-init="initSwiperswiperclaimRatings() "
        x-on:click="stopSwiperswiperclaimRatings(); setTimeout(() => startSwiperswiperclaimRatings(), 5000)"

        class=" relative w-full"
        wire:ignore
    >
        {{-- Navigation links/rechts außerhalb --}}
        
        <div class="swiper w-full" x-ref="swiperclaimRatings" >
            <div class="swiper-wrapper  !ease-linear">
                @foreach ($claimRatings as $claimRating)
                    <div class="swiper-slide h-full px-4">
                        <x-claim-rating.claim-rating-card :rating="$claimRating" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

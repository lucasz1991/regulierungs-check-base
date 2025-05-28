<div class="relative  overflow-hidden py-20 " style="background-image: url('/site-images/bg-green-blue.jpg'); background-size: cover; background-position: center; ">
    <div class="text-center mb-12" style="text-shadow:1px 1px 2px white;">
            <h4 class="font-medium text-4xl text-gray-700 "><span class="border-b-2  border-secondary pb-1">Aktuelle Bewertungen</span></h4>
            <p class="text-xl font-medium text-gray-700 mt-4 ">Hier finden Sie eine zufällige Auswahl an aktuellen Bewertungen.</p>
    </div>
    <div x-data="{
            swiperclaimRatings: null,
            initSwiperswiperclaimRatings() {
                this.swiperclaimRatings = new Swiper(this.$refs.swiperclaimRatings, {
                    autoplay: {
                        delay: 0,
                    },
                    disableOnInteraction: true,
                    speed: 15000,
                    loop: true,
                    centeredSlides: true,
                    slidesPerView: '1',
                    spaceBetween: 20,
                    disableOnMobile: true,
                    breakpoints: {
                        1000: {
                            slidesPerView: 2,
                        },
                        1200: {
                            slidesPerView: 3,
                        },
                        1400: {
                            slidesPerView: 4,
                        },
                    },
                    
                });
                this.swiperclaimRatings.slideNext();
            },
            stopSwiperswiperclaimRatings() {
                this.swiperclaimRatings.autoplay.stop();
            },
            startSwiperswiperclaimRatings() {
                this.swiperclaimRatings?.autoplay?.start();
            }
        }"
        x-init="initSwiperswiperclaimRatings() "
        x-on:click="this.swiperclaimRatings.stopSwiperswiperclaimRatings(); setTimeout(() => this.swiperclaimRatings.startSwiperswiperclaimRatings(), 5000)"

        class=" relative w-full"
        wire:ignore
    >
        <div class="swiper w-full  overflow-visible" x-ref="swiperclaimRatings" >
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

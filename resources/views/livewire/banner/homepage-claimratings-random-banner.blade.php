<div class="relative  overflow-hidden py-20 " style="background-image: url('/site-images/bg-green-blue.jpg'); background-size: cover; background-position: center; ">
    <div class="container px-5 mx-auto text-center mb-12" style="text-shadow:1px 1px 2px white;">
            <h4 class="font-medium text-2xl md:text-4xl text-gray-700 "><span class="border-b-2  border-secondary pb-1">Aktuelle Bewertungen</span></h4>
            <p class="md:text-xl font-medium text-gray-700 mt-4 ">Hier findest du eine zufällige Auswahl an aktuellen Bewertungen.</p>
    </div>
    <div x-data="{
            showSwiperNavigation: false,
            swiperclaimRatings: null,
            initSwiperswiperclaimRatings() {
                this.swiperclaimRatings = new Swiper(this.$refs.swiperclaimRatings, {
                    autoplay: {
                        delay: 2500,
                    },
                    disableOnInteraction: true,
                    speed: 500,
                    effect: 'slide',
                    pagination: {
                        el: '.swiper-pagination-claimRatings',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next-claimRatings',
                        prevEl: '.swiper-button-prev-claimRatings',
                    },
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
                this.showSwiperNavigation = true;
            },
            startSwiperswiperclaimRatings() {
                this.swiperclaimRatings?.autoplay?.start();
                this.showSwiperNavigation = false;
            }
        }"
        x-init="initSwiperswiperclaimRatings() "
            x-on:click="stopSwiperswiperclaimRatings()"
            x-on:click.away="startSwiperswiperclaimRatings()"
            x-on:touchstart="stopSwiperswiperclaimRatings()"
        class=" relative w-full"
        wire:ignore
        wire:loading.class="hidden"
    >
        <div class="swiper w-full  overflow-visible" x-ref="swiperclaimRatings" >
            <div  class="swiper-wrapper   transform-gpu">
                @foreach ($claimRatings as $claimRating)
                    <div class="swiper-slide h-full px-4  transform-gpu">
                        <x-claim-rating.claim-rating-card :rating="$claimRating" />
                    </div>
                @endforeach
            </div> 
            <div x-show="showSwiperNavigation" x-cloak x-transition class="swiper-pagination swiper-pagination-claimRatings !-bottom-12"></div>
            <div x-show="showSwiperNavigation" x-cloak x-transition class="swiper-button-next swiper-button-next-claimRatings"></div>
            <div x-show="showSwiperNavigation" x-cloak x-transition class="swiper-button-prev swiper-button-prev-claimRatings"></div>
        </div>
    </div>
</div>

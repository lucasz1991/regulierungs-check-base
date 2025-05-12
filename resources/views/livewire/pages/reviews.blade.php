<div class=" bg-gray-50">
    <div class="container mx-auto px-4 py-8">

    
        {{-- Zusammenfassung --}}
        <div class="bg-white shadow rounded-lg p-6 mb-8">

            <p class="text-xl">Ø Bewertung: <span class="font-bold">{{ $average }}</span> ⭐</p>
            <p class="text-gray-600">{{ $totalCount }} Bewertungen insgesamt</p>
        </div>
    
        {{-- Einzelbewertungen --}}
        <div class="grid gap-4"
            x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.swiper, {
                        freeMode: {
                            enabled: true,
                            sticky: true,
                        },
                        autoplay: {
                            delay: 1800,
                        },
                        disableOnInteraction: true,
                        speed: 1000,
                        loop: true,
                        centeredSlides: true,
                        slidesPerView: '2',
                        disableOnMobile: true,
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 40,
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
                }
            }"
            x-init="initSwiper() "
            class=" relative w-full"
            wire:ignore>
            <style>
                .swiper {
                    padding: 0px 0px 60px 0px;
                }
                .swiper-pagination.swiper-pagination-clickable.swiper-pagination-bullets {
                    
                }
            </style>
            <div class="swiper w-full" x-ref="swiper" >
                <div class="swiper-wrapper pointer-events-none">
                    @foreach ($ratings as $rating)
                        <div class="bg-white p-4 rounded shadow swiper-slide h-full"  wire:key="rating-{{ $rating->id }}">
                            <div class="grid grid-cols-12 gap-4 mb-4">
                                <div class="col-span-2 pr-4">
                                    <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-base font-bold" style="background-color: {{ $rating->insurance->color ?? '#ccc' }};">
                                        {{ strtoupper(substr( $rating->insurance->initials, 0 ,4)) }}
                                    </div>
                                </div>
                                <div class="col-span-10">
                                    <h2 class="text-xl break-words font-semibold mb-2">
                                        {{ $rating->insurance->name }}
                                    </h2>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <x-insurance.insurance-rating-stars :score="$rating->insurance->claim_ratings_avg_rating_score" />

                                <div class="text-sm text-gray-500">
                                     {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                                </div>
                            </div>
                            <div class="mt-2 text-gray-800">
                                {{ $rating->comment }}
                            </div>
                        </div>
                    @endforeach
                </div>
                  <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

</div>

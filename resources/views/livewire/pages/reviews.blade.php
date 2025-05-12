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
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-lg">
                                    {{ $rating->insurance->name ?? 'Versicherung unbekannt' }}
                                </div>
                                <div class="text-yellow-400 font-bold">
                                    {{ $rating->rating }} ★
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Regulierung: {{ ucfirst($rating->regulation_type) }} – {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
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

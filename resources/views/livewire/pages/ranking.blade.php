<div class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">

        {{-- TOP 5 SWIPER --}}
        <div class="my-12">
            <h2 class="text-xl font-bold mb-4 text-green-900">Top 5 Versicherungen</h2>

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
                }
            }"
            x-init="initSwiper()"
            class="relative"
            wire:ignore>
                <div class="swiper" x-ref="topSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($top5 as $insurance)
                            <div class="swiper-slide bg-white p-4 rounded shadow h-full">
                                <div class="grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-2 pr-4">
                                        <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                                            {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                                        </div>
                                    </div>
                                    <div class="col-span-10">
                                        <h2 class="text-xl break-words font-semibold mb-2">
                                        {{ Str::limit($insurance->name, 30)}}
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <div class=""><x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" /></div>
                                </div>
                                <p class="text-sm text-gray-600">{{ $insurance->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- FLOP 5 SWIPER --}}
        <div class="my-12">
            <h2 class="text-xl font-bold mb-4 text-red-900">Flop 5 Versicherungen</h2>

            <div x-data="{
                swiper: null,
                initSwiper() {
                    this.swiper = new Swiper(this.$refs.flopSwiper, {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        autoplay: {
                            delay: 1500,
                        },
                        speed: 2500,
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination-flop',
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
            wire:ignore>
                <div class="swiper" x-ref="flopSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($flop5 as $insurance)
                            <div class="swiper-slide bg-white p-4 rounded shadow h-full">
                                <div class="grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-2 pr-4">
                                        <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                                            {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                                        </div>
                                    </div>
                                    <div class="col-span-10">
                                        <h2 class="text-xl break-words font-semibold mb-2">
                                        {{ Str::limit($insurance->name, 30)}}
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <div class=""><x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" /></div>
                                </div>
                                <p class="text-sm text-gray-600">{{ $insurance->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- Gesamtranking --}}
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Gesamtranking</h2>
            <div class="flex flex-col">
                @foreach ($allInsurances as $insurance)
                    <div class="p-4  flex items-center justify-between">
                        <div>
                                <div class="grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-2 pr-4">
                                        <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                                            {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                                        </div>
                                    </div>
                                    <div class="col-span-10">
                                        <h2 class="text-xl break-words font-semibold mb-2">
                                        {{ Str::limit($insurance->name, 30)}}
                                        </h2>
                                    </div>
                                </div>
                            <p class="text-gray-600 text-sm">{{ $insurance->description }}</p>
                        </div>
                        <div class="text-yellow-500 font-bold text-xl">
                        <div class=""><x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" /></div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

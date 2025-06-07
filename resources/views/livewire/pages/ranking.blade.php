<div class="">
    <div class="bg-blue-50">
    <div class="container mx-auto px-6 md:px-12 py-8">
        <div>
            <h1 class="text-xl mb-2 text-gray-700">Ranking & Auszeichnungen filtern</h1>
            <p class="mb-4 max-w-lg text-gray-600 text-base">
                Hier können Sie das Ranking und die Auszeichnungen der Versicherungen nach verschiedenen Versicherungsarten filtern. Wählen Sie einfach die gewünschte Versicherungsart aus, um die Ergebnisse entsprechend anzupassen.
            </p>
        </div>
        <label class="text-base text-gray-600 mb-1 flex justify-left space-x-2 align-middle content-center px-2">
            <svg class="w-4 stroke-current stroke-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50">
            <path d="M 20 3 C 18.355469 3 17 4.355469 17 6 L 17 9 L 3 9 C 1.355469 9 0 10.355469 0 12 L 0 26.8125 C -0.0078125 26.875 -0.0078125 26.9375 0 27 L 0 44 C 0 45.644531 1.355469 47 3 47 L 47 47 C 48.644531 47 50 45.644531 50 44 L 50 12 C 50 10.355469 48.644531 9 47 9 L 33 9 L 33 6 C 33 4.355469 31.644531 3 30 3 Z M 20 5 L 30 5 C 30.5625 5 31 5.4375 31 6 L 31 9 L 19 9 L 19 6 C 19 5.4375 19.4375 5 20 5 Z M 3 11 L 47 11 C 47.5625 11 48 11.4375 48 12 L 48 26.84375 C 48 26.875 48 26.90625 48 26.9375 L 48 27 C 48 27.5625 47.5625 28 47 28 L 3 28 C 2.4375 28 2 27.5625 2 27 C 2.007813 26.9375 2.007813 26.875 2 26.8125 L 2 12 C 2 11.4375 2.4375 11 3 11 Z M 25 22 C 23.894531 22 23 22.894531 23 24 C 23 25.105469 23.894531 26 25 26 C 26.105469 26 27 25.105469 27 24 C 27 22.894531 26.105469 22 25 22 Z M 2 29.8125 C 2.316406 29.925781 2.648438 30 3 30 L 47 30 C 47.351563 30 47.683594 29.925781 48 29.8125 L 48 44 C 48 44.5625 47.5625 45 47 45 L 3 45 C 2.4375 45 2 44.5625 2 44 Z"></path>
            </svg>                                
            <span>Versicherungsarten</span>
        </label>
        <div class=" w-max flex items-end justify-left space-x-2">
            <div style="" >
                <x-filter.filter-dropdown-checkbox wire:model="selectedInsuranceSubTypefilter"  :options="$insuranceSubTypes" />
            </div>
            <button class="!px-2 !py-2.5 h-full bg-secondary text-sm text-white  rounded" wire:click="submitFilters">
                anzeigen 
            </button>
        </div>
    </div>
    </div>
    <div class="bg-gray-50">
        <div class="container mx-auto px-4 md:px-12 py-8">
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
                wire:ignore>
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


            {{-- Gesamtranking --}}
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-4 text-gray-600">Gesamtranking</h2>
                <div class="flex flex-col space-y-4">
                    @foreach ($allInsurances as $insurance)
                    <div class="flex items-center justify-between mb-2">
                            <div class="w-16 shrink-0 mr-6 flex items-center justify-center" >
                                <span class="inline-flex items-center justify-center rounded-full text-lg font-semibold
                                    @if($loop->iteration == 1)   w-18 h-18
                                    @elseif($loop->iteration == 2)   w-16 h-16
                                    @elseif($loop->iteration == 3)   w-14 h-14
                                    @else bg-gray-100 text-gray-400  w-8 h-8
                                    @endif
                                ">
                                    @if($loop->iteration == 1) 
                                        <img src="{{ asset('/site-images/place1.png') }}" alt="">                                    
                                    @elseif($loop->iteration == 2) 
                                        <img src="{{ asset('/site-images/place2.png') }}" alt="">                                    
                                    @elseif($loop->iteration == 3) 
                                        <img src="{{ asset('/site-images/place3.png') }}" alt="">                                    
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </span>
                            </div>
                            <div class="grow">
                                <x-insurance.insurance-card :insurance="$insurance" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

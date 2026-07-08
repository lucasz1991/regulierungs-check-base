@props(['images' => [], 'title' => 'News'])

@php
    $images = array_values($images);
    $swiperId = 'news-swiper-' . uniqid();
@endphp

@if(count($images) === 1)
    <figure class="overflow-hidden rounded-2xl bg-gray-100 shadow-lg">
        <img
            src="{{ $images[0]['url'] }}"
            alt="{{ $images[0]['alt'] ?? $title }}"
            class="h-full max-h-[520px] w-full object-cover"
            loading="lazy"
        >
        @if(!empty($images[0]['caption']))
            <figcaption class="bg-white px-4 py-3 text-sm text-gray-600">{{ $images[0]['caption'] }}</figcaption>
        @endif
    </figure>
@elseif(count($images) > 1)
    <div
        x-data="{
            swiper: null,
            initSwiper() {
                this.swiper = new Swiper(this.$refs.slider, {
                    slidesPerView: 1,
                    loop: true,
                    autoHeight: true,
                    pagination: {
                        el: this.$refs.pagination,
                        clickable: true,
                    },
                    navigation: {
                        nextEl: this.$refs.next,
                        prevEl: this.$refs.prev,
                    },
                });
            }
        }"
        x-init="initSwiper()"
        class="relative overflow-hidden rounded-2xl bg-gray-100 shadow-lg"
        wire:ignore
    >
        <div class="swiper" x-ref="slider" id="{{ $swiperId }}">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <figure class="swiper-slide bg-gray-100">
                        <img
                            src="{{ $image['url'] }}"
                            alt="{{ $image['alt'] ?? $title }}"
                            class="max-h-[520px] w-full object-cover"
                            loading="lazy"
                        >
                        @if(!empty($image['caption']))
                            <figcaption class="bg-white px-4 py-3 text-sm text-gray-600">{{ $image['caption'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>

        <button type="button" x-ref="prev" class="absolute left-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-gray-800 shadow hover:bg-white">
            <i class="fal fa-chevron-left"></i>
        </button>
        <button type="button" x-ref="next" class="absolute right-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-gray-800 shadow hover:bg-white">
            <i class="fal fa-chevron-right"></i>
        </button>
        <div x-ref="pagination" class="swiper-pagination"></div>
    </div>
@endif

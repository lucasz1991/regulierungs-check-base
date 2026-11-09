@props(['images' => [], 'title' => 'News'])

@php
    $images = array_values($images);
    $swiperId = 'news-swiper-' . uniqid();
@endphp

@if(count($images) === 1)
    <figure class="overflow-hidden rounded-2xl bg-gray-100 shadow-lg">
        <div x-data="{ imageFailed: false }">
            <div x-show="imageFailed" x-cloak class="flex aspect-[16/9] items-center justify-center bg-gradient-to-br from-primary via-primary-light to-secondary text-4xl text-white/80" role="img" aria-label="{{ $images[0]['alt'] ?? $title }}">
                <i class="fal fa-newspaper" aria-hidden="true"></i>
            </div>
            <img
                src="{{ $images[0]['url'] }}"
                alt="{{ $images[0]['alt'] ?? $title }}"
                class="h-full max-h-[520px] w-full object-cover"
                loading="lazy"
                x-show="!imageFailed"
                x-init="$nextTick(() => { if ($el.complete && !$el.naturalWidth) imageFailed = true; })"
                x-on:error="imageFailed = true"
                x-on:load="imageFailed = false"
            >
        </div>
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
        x-on:news-media-updated="$nextTick(() => swiper?.updateAutoHeight())"
        class="relative overflow-hidden rounded-2xl bg-gray-100 shadow-lg"
        wire:ignore
    >
        <div class="swiper" x-ref="slider" id="{{ $swiperId }}">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <figure class="swiper-slide bg-gray-100" x-data="{ imageFailed: false }">
                        <div x-show="imageFailed" x-cloak class="flex aspect-[16/9] items-center justify-center bg-gradient-to-br from-primary via-primary-light to-secondary text-4xl text-white/80" role="img" aria-label="{{ $image['alt'] ?? $title }}">
                            <i class="fal fa-newspaper" aria-hidden="true"></i>
                        </div>
                        <img
                            src="{{ $image['url'] }}"
                            alt="{{ $image['alt'] ?? $title }}"
                            class="max-h-[520px] w-full object-cover"
                            loading="lazy"
                            x-show="!imageFailed"
                            x-init="$nextTick(() => { if ($el.complete && !$el.naturalWidth) { imageFailed = true; $dispatch('news-media-updated'); } })"
                            x-on:error="imageFailed = true; $dispatch('news-media-updated')"
                            x-on:load="imageFailed = false; $dispatch('news-media-updated')"
                        >
                        @if(!empty($image['caption']))
                            <figcaption class="bg-white px-4 py-3 text-sm text-gray-600">{{ $image['caption'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>

        <button type="button" x-ref="prev" aria-label="Vorheriges Bild" class="absolute left-3 top-1/2 z-10 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-gray-800 shadow hover:bg-white">
            <i class="fal fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button type="button" x-ref="next" aria-label="Nächstes Bild" class="absolute right-3 top-1/2 z-10 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-gray-800 shadow hover:bg-white">
            <i class="fal fa-chevron-right" aria-hidden="true"></i>
        </button>
        <div x-ref="pagination" class="swiper-pagination"></div>
    </div>
@endif

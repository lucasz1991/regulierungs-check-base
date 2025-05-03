    @if ($isWebPage && $showHeader)
        <header class="relative bg-cover bg-center min-h-32" 
        style="background-image: url('{{ $header_image ? url('public/' . $header_image) : asset('site-images/background.jpg') }}');">
        <div class="absolute inset-0 "></div>
            <div class="relative container mx-auto py-4 text-2xl px-4 sm:px-6 lg:px-8 space-y-12">
                <x-back-button />
                <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                    {{ $title }}
                    
                    @if (!empty($icon))
                        <div class="pageheader-icon w-12 aspect-square text-[#333] ml-10  inline opacity-30">
                            {!! $icon !!}
                        </div>
                    @endif
                </h1>
            </div>
        </header>
    @endif
    @if ($isWebPage && $showHeader)
        <header class="relative bg-cover bg-center min-h-32  px-8 " style="background-image: url('{{ $header_image ? url('storage/' . $header_image) : asset('site-images/background.jpg') }}');">
            <div class="absolute inset-0 bg-blue-50 opacity-70"></div>
            <div class="relative container mx-auto py-12 text-2xl  space-x-6 flex justify-start  items-center">
                <x-back-button />
                <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                    {!! $title !!}
                    @if (!empty($icon))
                        <div class="pageheader-icon w-12 aspect-square text-[#333] ml-10  inline opacity-10 md:opacity-30 max-md:absolute  max-md:right-0  max-md:top-3">
                            {!! $icon !!}
                        </div>
                    @endif
                </h1>
            </div>
        </header>
    @endif
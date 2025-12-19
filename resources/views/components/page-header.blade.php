    @if ($isWebPage && $showHeader)
        <header class="relative" >
            
            <div class="relative container mx-auto px-4  py-8 text-2xl  space-x-6 flex justify-start  items-center">
                <x-back-button />
                <h1 class=" text-xl text-white/80 leading-tight flex items-center">
                    {!! $title !!}
                    @if (!empty($icon))
                        <div class="pageheader-icon w-12 aspect-square text-white ml-10  inline opacity-10 md:opacity-30 max-md:absolute  max-md:right-0  max-md:top-3">
                            {!! $icon !!}
                        </div>
                    @endif
                </h1>
            </div>
        </header>
    @endif
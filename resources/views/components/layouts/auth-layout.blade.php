<div>
      <div class="lg:grid lg:min-h-[70vh] lg:grid-cols-12">

        {{-- Linke Seite mit Bild und Text --}}
        <section class="relative flex h-32 items-center justify-end lg:col-span-5 lg:h-full xl:col-span-6">
          <img
            alt=""
            src="{{ asset('site-images/background.jpg') }}"
            class="absolute inset-0 h-full w-full object-cover opacity-80"
          />
          <div class="absolute inset-0 bg-blue-50 opacity-80"></div>

          <div class="hidden lg:relative lg:block lg:p-12">
            <a class="block text-white" href="#">
              <span class="sr-only">Home</span>
              <div class="w-[300px]">
                  <x-authentication-card-logo  />
                </div>
            </a>
            <h2 class="mt-6 text-2xl font-bold  sm:text-3xl md:text-4xl color-primary">
            {{ $title }}
            </h2>
            <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
                {{ $description }}
            </p>
          </div>
        </section>

        {{-- Rechte Seite mit Formular --}}
        <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
          <div class="max-w-xl lg:max-w-3xl">
            <div class="relative -mt-16 block lg:hidden">
                <a
                    class="inline-flex p-2 items-center justify-center rounded-full bg-white text-blue-600 sm:size-20"
                    href="#"
                >
                    <span class="sr-only">Home</span>
                    <div class="w-20">
                    <x-authentication-card-logo  />
                    </div>
                </a>
                <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                {{ $title }}
                </h1>
    
                <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
                {{ $description }}                
                </p>
            </div>

            {{ $form }}
          </div>
        </main>
      </div>
</div>
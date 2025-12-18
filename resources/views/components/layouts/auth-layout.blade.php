<div class="min-h-[80vh] container px-4 mx-auto flex items-center">
    <div class="lg:grid lg:min-h-screen lg:grid-cols-12">

        {{-- Linke Seite: Bild + Text --}}
        <section class="relative lg:col-span-5  hidden lg:flex items-center">


            <div class="relative z-10 p-12 max-w-xl md:mb-24">
                <div class="w-56 mb-10">
                    <x-authentication-card-logo />
                </div>

                <h2 class="text-3xl xl:text-4xl font-semibold text-white leading-tight">
                    {{ $title }}
                </h2>

                <p class="mt-5 text-lg text-blue-100/80 leading-relaxed">
                    {{ $description }}
                </p>
            </div>
        </section>

        {{-- Rechte Seite: Formular --}}
        <main class="lg:col-span-7  flex items-center justify-center px-2 md:px-6 sm:px-10 md:py-12">
            <div class="w-full ">

                {{-- Mobile Header --}}
                <div class="lg:hidden mb-8 text-center">
                    <div class="mx-auto w-20 mb-4">
                        <x-authentication-card-logo />
                    </div>

                    <h1 class="text-2xl font-semibold text-white">
                        {{ $title }}
                    </h1>

                    <p class="mt-3 text-sm text-blue-100/80 leading-relaxed">
                        {{ $description }}
                    </p>
                </div>

                {{-- Formular-Card --}}
                <div class="rounded-3xl bg-white/95 backdrop-blur border border-white/10 shadow-2xl py-4 md:py-8 px-4 md:px-6">
                    {{ $form }}
                </div>
            </div>
        </main>

    </div>
</div>

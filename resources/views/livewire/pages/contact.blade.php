<div class=" ">

    {{-- Intro / Kontaktinfos --}}
    <section class=" pb-6 relative z-10">
        <div class="container px-4  mx-auto">

            <div class="rounded-3xl border border-white/10 bg-white/10 backdrop-blur-xl shadow-2xl  p-4 md:p-6 lg:p-10">
                <div class="flex max-lg:flex-wrap gap-6">

                    {{-- Text --}}
                    <div class="w-full md:w-1/2">
                        <h1 class="text-2xl md:text-3xl font-semibold text-white mb-4">
                            Kontakt
                        </h1>
                        <p class="text-white leading-relaxed">
                            Vielen Dank für dein Interesse an Regulierungs-Check! Wenn du Fragen zur Bewertung von Versicherungen hast,
                            Unterstützung bei der Nutzung unserer Analysetools benötigst oder weitere Informationen rund um Versicherungsvergleiche
                            wünschst, stehen wir dir gerne zur Verfügung.
                        </p>
                    </div>

                    {{-- Cards rechts --}}
                    <div class="w-full md:w-1/2">
                        <div class="">

                                <div class="rounded-2xl bg-white/95 border border-white/10 shadow p-6">

                                    <div class="flex items-start gap-5">
                                        <!-- Großes Icon -->
                                        <div class="shrink-0">
                                            <div class="inline-flex items-center justify-center rounded-2xl
                                                        bg-gray-50
                                                        text-gray-500 w-12 h-12 shadow-lg">
                                                <i class="fal fa-headset text-xl"></i>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="min-w-0">
                                            <p class="text-lg font-semibold text-gray-900 mb-1">
                                                Support & Kontakt
                                            </p>

                                        </div>
                                        
                                    </div>
                                    <div class="sm:ml-16 space-y-4">
                                        <!-- Mail -->
                                        <div class="flex items-center gap-3 mt-3">
                                            <a href="mailto:info@regulierungs-check.de"
                                            class="inline-flex items-center justify-center
                                                    w-12 h-12 rounded-xl
                                                    bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600
                                                    text-white shadow-md
                                                    hover:scale-105 transition shrink-0"
                                            aria-label="E-Mail">
                                                <i class="fal fa-envelope text-lg"></i>
                                            </a>

                                            <a href="mailto:info@regulierungs-check.de"
                                            class="text-sm font-medium text-gray-800 hover:text-blue-600 transition">
                                                info@regulierungs-check.de
                                            </a>
                                        </div>

                                        <!-- Instagram -->
                                        <div class="flex items-center gap-3 mt-4">
                                            <a href="https://www.instagram.com/regulierungs_check?igsh=MWV2MWI5NTk0NWtscQ%3D%3D&utm_source=qr"
                                            target="_blank"
                                            class="inline-flex items-center justify-center
                                                    w-12 h-12 rounded-xl
                                                    bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500
                                                    text-white shadow-md
                                                    hover:scale-105 transition  shrink-0"
                                            aria-label="Instagram">
                                                <i class="fab fa-instagram text-xl"></i>
                                            </a>

                                            <span class="text-sm text-gray-500">
                                                Folge uns auf Instagram
                                            </span>
                                        </div>
                                    </div>

                                </div>


                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>


    {{-- Formular Bereich --}}
    <section class="pb-14 relative z-10">
        <div class="container px-4 mx-auto">

            {{-- Dark wrapper, innen helle Cards --}}
            <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">

                <div class="grid grid-cols-1 md:grid-cols-3">
                    {{-- Bild --}}
                    <div class="order-1 md:order-2 h-64 md:h-full">
                        <img
                            class="h-full w-full object-cover object-center opacity-90"
                            src="{{ asset('/site-images/contact_img1.jpg') }}"
                            alt="Kontakt"
                            loading="lazy"
                        />
                    </div>

                    {{-- Formular --}}
                    <div class="order-2 md:order-1 md:col-span-2 bg-white/95 p-4 md:p-6 lg:p-10">

                        <div class="md:pr-12">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Kontaktiere uns
                            </h2>
                            <p class="mt-2 text-gray-600">
                                Egal, ob du Fragen, Vorschläge oder Wünsche hast – wir sind für dich da. Schreib uns einfach!
                            </p>

                            {{-- Success --}}
                            @if (session()->has('success'))
                                <div class="mt-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 flex items-start gap-3">
                                    <i class="fal fa-check-circle mt-0.5"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                            @endif

                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div class="md:col-span-2">
                                    <x-label for="name" value="Dein Name" />
                                    <x-input wire:model="name" id="name" class="block mt-1 w-full"
                                             type="text" name="name" required placeholder="Max Mustermann" />
                                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <x-label for="email" value="Deine E-Mail" />
                                    <x-input wire:model="email" id="email" class="block mt-1 w-full"
                                             type="email" name="email" required placeholder="name@beispiel.de" />
                                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <x-label for="subject" value="Betreff" />
                                    <x-input wire:model="subject" id="subject" class="block mt-1 w-full"
                                             type="text" name="subject" required placeholder="Worum geht es?" />
                                    @error('subject') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <x-label for="message" value="Deine Nachricht" />
                                    <textarea wire:model="message" id="message" name="message" rows="6" required
                                        class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Schreibe deine Nachricht hier..."></textarea>
                                    @error('message') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2 flex items-center justify-end pt-2">
                                    <button
                                        wire:click="send"
                                        class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-medium
                                               bg-blue-600 text-white shadow-lg shadow-blue-600/20
                                               hover:bg-blue-500 transition
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white"
                                    >
                                        <i class="fal fa-paper-plane"></i>
                                        <span>Nachricht senden</span>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
</div>

<div  wire:loading.class="cursor-wait">
  <section class="relative" style="background-image: url('/site-images/background.jpg'); background-size: cover; background-position: 50% 80%;">
    <div class="absolute inset-0 bg-blue-50 opacity-80">
    </div>
    <div class="max-w-4xl mx-auto text-center  py-16 px-6 md:px-12 relative z-10">
      <h2 class="text-3xl md:text-4xl  text-gray-800 mb-4 mt-5">
        Bewerte deine Versicherung
      </h2>
      <p class="text-lg md:text-xl text-gray-700 pb-8">
        fair, anonym und öffentlich. Erfahre, wie schnell und gerecht andere Kunden entschädigt wurden. Gemeinsam schaffen wir Transparenz im Versicherungsdschungel.
      </p>
      <div>
        <div class="flex space-x-4 items-center justify-center mb-4 w-full">
            <livewire:customer.rating.rating-form />
            <x-buttons.button-basic href="/insurances" :mode="'secondary'">
                Vergleichen
            </x-buttons.button-basic>
        </div>
    </div>
      <div class="mt-10">
        <p class="text-gray-600 mb-3">Top-Versicherungen im Ranking:</p>
        <div class="flex flex-wrap justify-center gap-4">
          <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">HanseMerkur</span>
          <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">Allianz</span>
          <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">HUK24</span>
        </div>
      </div>
    </div>
  </section>
  
</div>

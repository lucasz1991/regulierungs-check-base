<div  wire:loading.class="cursor-wait">
  <section class="relative home-banner  grid content-center overflow-hidden kenburns" 
    :style="'height:' + 'calc(70dvh - ' + $store.nav.height + 'px);'" >
      <img  class="h-full w-full !object-center !object-cover absolute" src="/site-images/start-bg-2.jpg" alt="">
      <div class="absolute inset-0 bg-blue-100 opacity-20"></div>
      <div class="container mx-auto" >
        <div data-aos="zoom-in-down" data-aos-duration="1000" data-aos-delay="500" class="max-w-2xl max-md:mx-auto text-center md:text-left space-y-4 md:py-6 bg-white/50 md:bg-primary/70 rounded-xl  px-6 md:px-12 relative h-max z-10">
          <h2 class="text-2xl md:text-4xl font-semibold text-gray-800 md:text-white mb-4 mt-5">
            Bewerte deine Versicherung
          </h2>
          <p class="text-xl md:text-xl text-gray-700 md:text-white pb-8">
            fair, anonym und öffentlich. Erfahre, wie schnell und gerecht andere Kunden entschädigt wurden. Gemeinsam schaffen wir Transparenz im Versicherungsdschungel.
          </p>
          <div>
            <div class="flex space-x-4 items-center  mb-4 w-full" >
                <livewire:customer.rating.rating-form  />
                <x-buttons.button-basic href="/insurances" :mode="'success'">
                    Vergleiche
                </x-buttons.button-basic>
            </div>
          </div>
        </div>
      </div>
  </section>
      <x-pagebuilder-module :position="'content_between_1'"/>
  <section>
      <livewire:banner.homepage-counter-banner  />
  </section>
      <x-pagebuilder-module :position="'content_between_2'"/>
  <section>
      <livewire:banner.homepage-claimratings-random-banner  />
  </section>
  <livewire:testphase.modal-notice lazy />
</div>

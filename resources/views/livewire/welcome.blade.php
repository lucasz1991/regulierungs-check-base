<div  wire:loading.class="cursor-wait">
  <section class="relative home-banner  grid content-center overflow-hidden kenburns" 
    :style="'height:' + 'calc(70dvh - ' + $store.nav.height + 'px);'">
      <img  class="h-full w-full !object-center !object-cover absolute" src="/site-images/start-bg-2.jpg" alt="">
      <div class="absolute inset-0 bg-blue-100 opacity-20"></div>
      <div x-data="{ shown: false }" x-intersect="setTimeout(() => { shown = true }, 50)"
            class="container mx-auto" >
        <div  x-show="shown" 
              x-transition.delay.600ms.duration.800ms 
              class="max-w-2xl max-md:mx-auto text-center md:text-left space-y-4 md:py-6 bg-white/50 md:bg-primary/70 rounded-xl  px-6 md:px-12 relative h-max z-10">
          <h2 class="text-2xl md:text-4xl font-semibold text-gray-800 md:text-white mb-4 mt-5">
            Bewerte deine Versicherung
          </h2>
          <p class="text-xl md:text-xl text-gray-700 md:text-white pb-8">
            fair, anonym und öffentlich. Erfahre, wie schnell und gerecht andere Kunden entschädigt wurden. Gemeinsam schaffen wir Transparenz im Versicherungsdschungel.
          </p>
          <div>
            <div class="flex space-x-4 items-center  mb-4 w-full">
                <livewire:customer.rating.rating-form  />
                <x-buttons.button-basic href="/insurances" :mode="'success'">
                    Erfahren
                </x-buttons.button-basic>
            </div>
          </div>
        </div>
      </div>
  </section>
  <section>
    <livewire:banner.homepage-counter-banner  />
  </section>
  <section class="text-gray-600 body-font"><div id="izl9p-2" class="mx-auto flex px-5 py-24 md:flex-row flex-col items-center container"><div id="itlib-2" class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center"><h1 id="ilifi-2" class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Finde heraus, wie gut
          deine Versicherung wirklich ist.</h1><p id="icwk8-2" class="mb-8 leading-relaxed">Regulierungs-Check ist die erste unabhängige Plattform, auf der du
          echte Erfahrungen zur Schadensregulierung von Versicherungen findest – direkt von anderen Versicherten. Ob
          schnelle Auszahlung oder nerviger Papierkrieg: Hier erfährst du, welche Versicherungen fair handeln – und
          welche nicht.</p></div><div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6"><img alt="hero" src="http://dev.regulierungs-check.de/storage/pagebuilder_images/qKAmT4TcUzzx377rw4x1MxY9z0A0EsuiuJ4oJYV6.jpg" id="id9nt-2" class="object-cover object-center rounded"></div></div></section>
  <section>
    <livewire:banner.homepage-claimratings-random-banner  />
  </section>
  <livewire:testphase.modal-notice lazy />
</div>

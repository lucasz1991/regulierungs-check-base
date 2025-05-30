<div  wire:loading.class="cursor-wait">
      <x-pagebuilder-module :position="'content_between_1'"/>
      <section>
        <livewire:banner.homepage-counter-banner  />
      </section>
      <x-pagebuilder-module :position="'content_between_2'"/>
      <section>
        <livewire:banner.homepage-claimratings-random-banner  />
      </section>
      <livewire:customer.rating.rating-form   />
      <livewire:testphase.modal-notice lazy />
</div>

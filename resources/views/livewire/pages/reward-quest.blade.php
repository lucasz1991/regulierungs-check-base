<div wire:loading.class="cursor-wait" class="py-12 px-4 sm:px-6 lg:px-8">
    <x-dialog-modal wire:model="showTermsModal" :maxWidth="'4xl'">
        <x-slot name="title">
            Teilnahmebedingungen
        </x-slot>
        <x-slot name="content" class="">
            <div>
                <x-pagebuilder-module :position="'content_between_1'"/>

            </div>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.button-basic wire:click="$set('showTermsModal', false)" class="">
                Schließen
            </x-buttons.button-basic>
        </x-slot>
    </x-dialog-modal>
    <livewire:customer.rating.rating-form />
</div>
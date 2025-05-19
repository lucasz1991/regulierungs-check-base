
<div>

    <!-- Such-Modal -->
    <div x-data="{ open: @entangle('show') }"
         x-show="open"
         x-transition.opacity
         x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div 
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 m-4">
             <div name="title" class="border-b mb-4">
                Willkommen zur Testphase
            </div>

    <div name="content">
        <p class="text-gray-600 leading-relaxed">
            Diese Plattform befindet sich aktuell in der <strong>Testphase</strong>. Bitte nutze möglichst <strong>Testdaten</strong> und hilf uns mit deinem Feedback.
        </p>
    </div>

    <div name="footer" class="flex  justify-between content-center mt-4">
        <x-button wire:click="hide">
            Verstanden
        </x-button>

        <a href="{{ route('contact') }}">
            <x-button class="ml-3">
                Feedback geben
            </x-button>
        </a>
    </div>
        </div>
    </div>
</div>

<div class=" items-center justify-between p-4">
    
    <div class=" p-4 bg-yellow-50 border border-yellow-500 text-yellow-800 rounded-lg flex items-start gap-3 ">
        <svg class="w-6 h-6 mt-1 flex-none text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
        </svg>
        <div>
            <h3 class="font-semibold text-base mb-1">Noch keine detaillierte Auswertung</h3>
            <p class="text-sm">Für diese Versicherung liegen aktuell noch keine ausreichend bewerteten Fälle vor. Sobald erste Bewertungen eingegangen sind, wird hier eine Auswertung angezeigt.</p>
        </div>
    </div>
    <div class="flex items-center gap-2 mt-4">
        <a  href="{{ route('insurance.show-insurance', $insurance->slug) }}" class="text-blue-800 bg-gray-100 border border-gray-300 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center">
            Mehr erfahren
            <svg class="rtl:rotate-180 w-2.5 h-2.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>    
</div>
<div class="">
    <div class="bg-blue-50">
    <div class="container mx-auto px-6 md:px-12 py-8">
        <div>
            <h1 class="text-xl mb-2 text-gray-700">Ranking & Auszeichnungen filtern</h1>
            <p class="mb-4 max-w-lg text-gray-600 text-base">
                Hier können Sie das Ranking und die Auszeichnungen der Versicherungen nach verschiedenen Versicherungsarten filtern. Wählen Sie einfach die gewünschte Versicherungsart aus, um die Ergebnisse entsprechend anzupassen.
            </p>
        </div>
    </div>
    </div>
    <div class="bg-gray-50 pt-8">
        <x-filter.filter-container>
            <x-slot name="filters">
                <div class="p-2 mb-2" >
                    <label class="text-sm text-gray-400 px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                        <svg class="w-4 stroke-current stroke-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50">
                        <path d="M 20 3 C 18.355469 3 17 4.355469 17 6 L 17 9 L 3 9 C 1.355469 9 0 10.355469 0 12 L 0 26.8125 C -0.0078125 26.875 -0.0078125 26.9375 0 27 L 0 44 C 0 45.644531 1.355469 47 3 47 L 47 47 C 48.644531 47 50 45.644531 50 44 L 50 12 C 50 10.355469 48.644531 9 47 9 L 33 9 L 33 6 C 33 4.355469 31.644531 3 30 3 Z M 20 5 L 30 5 C 30.5625 5 31 5.4375 31 6 L 31 9 L 19 9 L 19 6 C 19 5.4375 19.4375 5 20 5 Z M 3 11 L 47 11 C 47.5625 11 48 11.4375 48 12 L 48 26.84375 C 48 26.875 48 26.90625 48 26.9375 L 48 27 C 48 27.5625 47.5625 28 47 28 L 3 28 C 2.4375 28 2 27.5625 2 27 C 2.007813 26.9375 2.007813 26.875 2 26.8125 L 2 12 C 2 11.4375 2.4375 11 3 11 Z M 25 22 C 23.894531 22 23 22.894531 23 24 C 23 25.105469 23.894531 26 25 26 C 26.105469 26 27 25.105469 27 24 C 27 22.894531 26.105469 22 25 22 Z M 2 29.8125 C 2.316406 29.925781 2.648438 30 3 30 L 47 30 C 47.351563 30 47.683594 29.925781 48 29.8125 L 48 44 C 48 44.5625 47.5625 45 47 45 L 3 45 C 2.4375 45 2 44.5625 2 44 Z"></path>
                        </svg>                                
                        <span>Arten:</span>
                    </label>
                    <x-filter.filter-dropdown-checkbox wire:model="selectedInsuranceSubTypefilter"  :options="$insuranceSubTypes" />
                </div>
                <div class="p-2 mb-2" >
                    <label class=" text-sm text-gray-400 px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                        <svg class="w-4 h-4 stroke-current stroke-2" viewBox="0 0 20 20">
                            <path class="stroke-current" fill="none" stroke="1" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                        </svg>
                        <span>
                            Aspekt
                        </span>
                    </label>
                    <select wire:model="selectedCategory" class="w-full px-3 py-2 border rounded-md text-sm border-blue-200">
                        <option value="allgemein">Allgemein</option>
                        <option value="geschwindigkeit">Geschwindigkeit</option>
                        <option value="kunden_service">Kunden Service</option>
                        <option value="fairness">Fairness</option>
                        <option value="transparenz">Transparenz</option>
                    </select>
                </div>
            </x-slot>
            <x-slot name="listContent">
                <div class="">
                
                    <div class="max-w-4xl">
                        <div class="flex flex-col space-y-4">
                            @foreach ($allInsurances as $insurance)
                            <div class="flex items-center justify-between mb-2">
                                    <div class="w-16 shrink-0 mr-6 flex items-center justify-center " >
                                        @php
                                            switch ($loop->iteration) {
                                                case 1:
                                                    $classes = '';
                                                    break;
                                                case 2:
                                                    $classes = '';
                                                    break;
                                                case 3:
                                                    $classes = '';
                                                    break;
                                                default:
                                                    $classes = 'bg-gray-100 text-gray-400';
                                                    break;
                                            }
                                        @endphp
                                        <span class="inline-flex items-center justify-center rounded-full text-lg font-semibold  w-8 h-8 {{ $classes }}">
                                            @if($loop->iteration == 1) 
                                                <img src="{{ asset('/site-images/place1.png') }}" alt="">                                    
                                            @elseif($loop->iteration == 2) 
                                                <img src="{{ asset('/site-images/place2.png') }}" alt="">                                    
                                            @elseif($loop->iteration == 3) 
                                                <img src="{{ asset('/site-images/place3.png') }}" alt="">                                    
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="grow"  style="max-width:calc(100% - 5.5rem);">
                                        <x-insurance.insurance-card :insurance="$insurance" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        </x-slot>
        </x-filter.filter-container>
    </div>
</div>

<div class="bg-blue-50 py-6  text-center border-t border-gray-300">
    <div class="container mx-auto">
        <section class="">
        <div class=" px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-white rounded-xl shadow-lg p-6 text-center" data-aos="fade-up" data-aos-delay="0">
    <div class="flex justify-between items-center mb-4">
        <div class="flex justify-center items-center text-xl font-semibold text-gray-800 mb-2 gap-2">
            <img src="{{ asset('site-images/beispiellogo-1.png') }}"  class="w-6 h-6">
            Versicherung XY 1
        </div>
        <div class="text-sm text-gray-500 mb-1">Art: Kfz</div>
    </div>
    <div class="flex justify-between items-center">
        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mb-1">Ø Dauer: 42 Tage</span>
        <x-insurance.insurance-rating-stars :score="0.6" :size="'lg'" />
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6 text-center" data-aos="fade-up" data-aos-delay="100">
    <div class="flex justify-between items-center mb-4">
        <div class="flex justify-center items-center text-xl font-semibold text-gray-800 mb-2 gap-2">
            <img src="{{ asset('site-images/beispiellogo-2.png') }}"  class="w-6 h-6">
            Versicherung XY 2
        </div>
        <div class="text-sm text-gray-500 mb-1">Art: Hausrat</div>
    </div>
    <div class="flex justify-between items-center">
        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mb-1">Ø Dauer: 18 Tage</span>
        <x-insurance.insurance-rating-stars :score="0.3" :size="'lg'" />
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6 text-center" data-aos="fade-up" data-aos-delay="200">
    <div class="flex justify-between items-center mb-4">
        <div class="flex justify-center items-center text-xl font-semibold text-gray-800 mb-2 gap-2">
            <img src="{{ asset('site-images/beispiellogo-3.png') }}"  class="w-6 h-6">
            Versicherung XY 3
        </div>
        <div class="text-sm text-gray-500 mb-1">Art: Haftpflicht</div>
    </div>
    <div class="flex justify-between items-center">
        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mb-1">Ø Dauer: 63 Tage</span>
        <x-insurance.insurance-rating-stars :score="0.9" :size="'lg'" />
    </div>
</div>

            </div>
        </div>
        </section>
    </div>
</div>

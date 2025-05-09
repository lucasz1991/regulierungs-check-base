<button   {!! $attributes->merge(['class' => 'transition-all duration-100 inline-flex items-center justify-center bg-gray-100 p-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-full aspect-square hover:bg-gray-200 focus:ring-4 focus:ring-gray-100 ']) !!} x-data="{ isClicked: false }" 
    @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
    style="transform:scale(1);"
    :style="isClicked ? 'transform:scale(0.9);' : ''">
    <svg class="transform rotate-180 h-8 aspect-square" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 521.4157 547.7315">
        <path fill="currentColor" d="M247.55,547.7315V401.1021H0V146.6276H247.55V0L521.4157,273.8658ZM10.5017,390.5968h247.55V522.3711L506.557,273.8658,258.0517,25.3604V157.1329H10.5017Z"/><path fill="currentColor" d="M292.2383,440.7386V356.1062H44.5219V191.6235H292.2383V107.0327L458.7602,273.864ZM65.5325,335.0992H313.249v54.8462L429.0789,273.8676,313.249,157.8187v54.8118H65.5325Z"/><path fill="currentColor" d="M336.9303,334.4333v-23.323H89.0473V236.6195h247.883v-22.954l58.9173,60.1822ZM120.5597,279.5979H346.2993l5.5584-5.714-5.6127-5.7521H120.5597Z"/>
    </svg>

</button>

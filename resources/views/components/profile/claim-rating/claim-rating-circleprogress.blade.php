<div x-data="{
                percentage: 5,
                strokeLength: 565.5, // 2π * 90
                get offset() {
                  return this.strokeLength - (this.percentage / 100) * this.strokeLength;
                }
              }" class="flex mr-3" >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1 opacity-40 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <g>
              <!-- Schild (Shield) -->
              <path stroke="currentColor" stroke-width="1.5" fill="#f3f4f6" d="M12 3l7 3v5c0 5.25-3.5 9.75-7 11-3.5-1.25-7-5.75-7-11V6l7-3z"/>
              <!-- Auge (Eye) -->
              <ellipse cx="12" cy="11" rx="3" ry="2" fill="#fff" stroke="currentColor" stroke-width="1"/>
              <circle cx="12" cy="11" r="0.8" fill="#1f2937"/>
              <path d="M9.5 11c.5-.7 1.5-1.2 2.5-1.2s2 .5 2.5 1.2" stroke="#6b7280" stroke-width="0.7" fill="none"/>
          </g>
      </svg>
      <div class="w-6 relative"  x-data="{ show: false }">
        <div  @mouseover="show = true" 
              @mouseleave="show = false"
              @click="show = true"
              @click.away="show = false" 
              x-ref="anchor"
              style="width: 100%; max-width: 300px;">
              <svg viewBox="0 0 200 200" style="width: 100%; height: auto;" preserveAspectRatio="xMidYMid meet">
                <!-- Grauer Hintergrundkreis -->
                <circle
                  cx="100"
                  cy="100"
                  r="80"
                  stroke="#ddd"
                  stroke-width="30"
                  fill="none"
                />
                <!-- Fortschrittskreis -->
                <circle
                  cx="100"
                  cy="100"
                  r="80"
                  stroke="#4CAF50"
                  stroke-width="30"
                  fill="none"
                  stroke-linecap="round"
                  stroke-dasharray="565.5"
                  :stroke-dashoffset="offset"
                  transform="rotate(-90 100 100)"
                />
              </svg>
        </div>
        <div x-show="show"  x-anchor.offset.10="$refs.anchor" class="z-50 text-sm text-white bg-gray-800 rounded-md shadow-lg p-3">
          Verrifizierungsstatus:<br><span class="font-semibold">Niedrig</span>
        </div>
    </div>
</div>

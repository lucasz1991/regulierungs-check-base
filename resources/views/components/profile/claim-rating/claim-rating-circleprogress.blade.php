<div
  x-data="{
    percentage: 5,
    strokeLength: 565.5, // 2π * 90
    get offset() {
      return this.strokeLength - (this.percentage / 100) * this.strokeLength;
    }
  }"
  style="width: 100%; max-width: 300px;"
>
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

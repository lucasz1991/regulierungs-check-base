<div  wire:loading.class="cursor-wait">
<section class="bg-gradient-to-br from-blue-50 to-blue-100 py-16 px-6 md:px-12">
  <div class="max-w-4xl mx-auto text-center">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
      Regulierungs-Check
    </h1>
    <p class="text-lg md:text-xl text-gray-700 mb-8">
      Bewerte deine Versicherung – fair, anonym und öffentlich. Erfahre, wie schnell und gerecht andere Kunden entschädigt wurden. Gemeinsam schaffen wir Transparenz im Versicherungsdschungel.
    </p>
    <livewire:customer.rating.rating-form />
    <div class="mt-10">
      <p class="text-gray-600 mb-3">Top-Versicherungen im Ranking:</p>
      <div class="flex flex-wrap justify-center gap-4">
        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">HanseMerkur</span>
        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">Allianz</span>
        <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">HUK24</span>
      </div>
    </div>
  </div>
</section>
<section class="bg-gray-50 py-16 px-6 md:px-12">
  <div class="max-w-5xl mx-auto text-center">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-10">
      Was möchtest du tun?
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Bewertung abgeben -->
      <div class="bg-white border border-gray-200 rounded-xl p-8 shadow hover:shadow-md transition">
        <div class="flex flex-col items-center">
          <div class="text-blue-600 text-5xl mb-4">📝</div>
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Versicherung bewerten</h3>
          <p class="text-gray-600 mb-6">Teile deine Erfahrung mit deiner Versicherung – fair, anonym und transparent.</p>
          <a href="/bewertung/neu" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
            Jetzt bewerten
          </a>
        </div>
      </div>

      <!-- Versicherungen vergleichen -->
      <div class="bg-white border border-gray-200 rounded-xl p-8 shadow hover:shadow-md transition">
        <div class="flex flex-col items-center">
          <div class="text-green-600 text-5xl mb-4">📊</div>
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Versicherungen vergleichen</h3>
          <p class="text-gray-600 mb-6">Finde heraus, welche Anbieter am schnellsten und fairsten regulieren – mit echten Daten.</p>
          <a href="/vergleich" class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
            Jetzt vergleichen
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-white py-12 px-6 md:px-12 border-t border-gray-200">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-8">
      Aktuelle Auswertungen & Rankings
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Top 3 Versicherungen -->
      <div class="bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-green-800 mb-4">Top 3 Versicherungen</h3>
        <ul class="space-y-2 text-green-700">
          <li>🥇 Allianz – 4.8 ⭐ | 3 Tage Bearbeitungszeit</li>
          <li>🥈 HanseMerkur – 4.6 ⭐ | 4 Tage</li>
          <li>🥉 Barmenia – 4.5 ⭐ | 5 Tage</li>
        </ul>
      </div>

      <!-- Flop 3 Versicherungen -->
      <div class="bg-red-50 border border-red-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-red-800 mb-4">Flop 3 Versicherungen</h3>
        <ul class="space-y-2 text-red-700">
          <li>🚫 XYZ Direkt – 1.8 ⭐ | 29 Tage</li>
          <li>🚫 MusterVersicherung24 – 2.1 ⭐ | 21 Tage</li>
          <li>🚫 SchnellAberNichtGut AG – 2.3 ⭐ | 19 Tage</li>
        </ul>
      </div>

      <!-- Statistiken -->
      <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">Allgemeine Statistiken</h3>
        <ul class="space-y-2 text-blue-700">
          <li>⏱ Durchschnittliche Bearbeitungszeit: <strong>8,3 Tage</strong></li>
          <li>⭐ Durchschnittliche Bewertung: <strong>3,9 Sterne</strong></li>
          <li>📊 Aktive Bewertungen: <strong>1.234 Fälle</strong></li>
        </ul>
      </div>
    </div>
  </div>
</section>
</div>

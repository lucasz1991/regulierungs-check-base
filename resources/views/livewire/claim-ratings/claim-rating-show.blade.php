<div class="bg-gray-50">
    <div class="container mx-auto px-4 py-6">
    
        <div class="bg-white rounded shadow p-6">
            <p class="text-sm text-gray-600 mb-1">
                Versicherung: <strong>{{ $claimRating->insurance->name ?? 'Keine Angabe' }}</strong>
            </p>
            <p class="text-sm text-gray-600 mb-1">
                Versicherungs-art: <strong>{{ $claimRating->insuranceType->name ?? 'Keine Angabe' }}</strong>
            </p>
    
            <p class="text-sm text-gray-600 mb-1">
                Bewertet mit: <strong>{{ number_format($claimRating->rating_score, 1) }}/5</strong>
            </p>
    
            <p class="text-sm text-gray-600 mb-4">
                Status: <span class="font-medium">{{ $claimRating->status }}</span>
            </p>
    
            <div class="prose max-w-full">
                <h2 class="text-lg font-semibold mb-2">Kommentar</h2>
                <p>{{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}</p>
            </div>
        </div>
    </div>
</div>

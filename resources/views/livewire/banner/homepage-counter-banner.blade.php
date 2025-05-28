<div class="bg-blue-50 py-16 text-center">
    <div class="container mx-auto">

        <div class="flex justify-between gap-12 items-center text-center  text-gray-800 p-8 rounded-lg shadow-inner">
            <div class="w-48" x-data="counterAnimation(1200)">
                <span class="text-4xl font-bold text-blue-900" x-text="counter">0</span>
                <p>Benutzer</p>
            </div>
            <div class="w-48" x-data="counterAnimation(530)">
                <span class="text-4xl font-bold text-blue-900" x-text="counter">0</span>
                <p>Versicherungen</p>
            </div>
            <div class="w-48" x-data="counterAnimation(1102)">
                <span class="text-4xl font-bold text-blue-900" x-text="counter">0</span>
                <p>Bewertungen</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('counterAnimation', (finalCount) => ({
            counter: 0,
            init() {
                const duration = 4500;
                const interval = 10;
                const step = Math.ceil(finalCount * interval / duration);

                const timer = setInterval(() => {
                    this.counter += step;
                    if (this.counter >= finalCount) {
                        this.counter = finalCount;
                        clearInterval(timer);
                    }
                }, interval);
            }
        }));
    });
</script>

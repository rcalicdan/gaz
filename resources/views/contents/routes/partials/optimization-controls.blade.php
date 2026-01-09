<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-magic text-emerald-600 mr-2"></i>
        Wyniki Optymalizacji
    </h2>

    <div class="space-y-4">
        <div x-show="!canOptimizeRoute && orders.length > 0" 
             class="bg-red-50 border-l-4 border-red-500 p-3 rounded-r text-sm">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                <div>
                    <p class="font-medium text-red-800">Wymagane działanie</p>
                    <p class="text-red-700 text-xs mt-1">
                        Nie można zoptymalizować trasy. <span x-text="coordinateValidationSummary.missing"></span> punktów nie posiada współrzędnych GPS.
                    </p>
                </div>
            </div>
        </div>

        <div x-show="!optimizationResult && canOptimizeRoute && orders.length > 0" class="text-center py-4 text-gray-500 text-sm">
            <i class="fas fa-arrow-circle-right text-2xl mb-2 text-emerald-400"></i>
            <p>Użyj przycisku w prawym dolnym rogu, aby wygenerować trasę.</p>
        </div>

        <div x-show="optimizationResult" x-transition class="grid grid-cols-2 gap-3 pt-2">
            <div class="bg-emerald-50 p-2 rounded text-center border border-emerald-100">
                <div class="text-xs text-emerald-600 font-bold uppercase">Dystans</div>
                <div class="text-lg font-bold text-gray-800" x-text="formatDistance(optimizationResult?.total_distance)"></div>
            </div>
            <div class="bg-blue-50 p-2 rounded text-center border border-blue-100">
                <div class="text-xs text-blue-600 font-bold uppercase">Czas</div>
                <div class="text-lg font-bold text-gray-800" x-text="formatTime(optimizationResult?.total_time)"></div>
            </div>
        </div>

        <div x-show="optimizationResult" class="flex flex-col gap-2 mt-2">
             <button @click="resetOptimization()"
                class="w-full py-2 px-4 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">
                <i class="fas fa-undo mr-1"></i> Resetuj wynik
            </button>
        </div>
    </div>
</div>
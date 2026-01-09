<div x-show="optimizationResult && showRouteSummary" 
     x-transition 
     class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-chart-pie text-emerald-600 mr-2"></i>
            Podsumowanie Trasy
        </h2>
        <div class="text-sm text-gray-500">
            Dla: <span class="font-semibold text-gray-800" x-text="selectedDriver?.full_name"></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div>
                <div class="text-sm text-blue-600 font-semibold uppercase tracking-wider">Czas Trasy</div>
                <div class="text-2xl font-bold text-gray-800" x-text="formatTime(optimizationResult?.total_time)"></div>
            </div>
        </div>

        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-road"></i>
            </div>
            <div>
                <div class="text-sm text-emerald-600 font-semibold uppercase tracking-wider">Dystans</div>
                <div class="text-2xl font-bold text-gray-800" x-text="formatDistance(optimizationResult?.total_distance)"></div>
            </div>
        </div>

        <div class="p-4 bg-purple-50 rounded-xl border border-purple-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-weight-hanging"></i>
            </div>
            <div>
                <div class="text-sm text-purple-600 font-semibold uppercase tracking-wider">Ładunek</div>
                <div class="text-2xl font-bold text-gray-800" x-text="orders.reduce((sum, o) => sum + (parseFloat(o.waste_quantity)||0), 0).toFixed(0) + ' kg'"></div>
            </div>
        </div>
    </div>
</div>
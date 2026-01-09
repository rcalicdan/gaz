<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6" x-data="{ open: false }">
    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center">
        <i class="fas fa-truck text-emerald-600 mr-3"></i>
        Kierowca
    </h2>

    <div x-show="loading" class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="text-sm text-yellow-800 flex items-center">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Pobieranie listy kierowców...
        </div>
    </div>

    <div x-show="!loading && selectedDriver && selectedDriver.id"
        class="p-4 rounded-lg border border-emerald-200 bg-emerald-50 mb-4 transition-all">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-gray-900 truncate flex items-center">
                    <div class="w-8 h-8 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700 mr-3 font-bold text-xs">
                        <i class="fas fa-user"></i>
                    </div>
                    <span x-text="selectedDriver?.full_name || 'Błąd danych'"></span>
                </div>
                <div class="text-xs text-gray-600 mt-2 pl-11">
                    Pojazd: <span class="font-medium" x-text="selectedDriver?.vehicle_details || 'Brak przypisanego'"></span>
                </div>
            </div>
        </div>
    </div>

    <div x-show="!loading && dataLoaded && (!selectedDriver || !selectedDriver.id)"
        class="p-6 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 mb-4 text-center">
        <div class="text-gray-500">
            <i class="fas fa-user-plus text-3xl text-gray-400 mb-3"></i>
            <div class="font-semibold">Wybierz kierowcę</div>
            <div class="text-xs mt-1">Aby zobaczyć jego harmonogram</div>
        </div>
    </div>

    <button @click="open = true" :disabled="loading || !dataLoaded"
        class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 text-white py-2.5 px-4 rounded-lg font-medium transition-colors shadow-sm flex items-center justify-center">
        <i class="fas fa-list mr-2"></i>
        <span x-text="(selectedDriver && selectedDriver.id) ? 'Zmień kierowcę' : 'Wybierz z listy'"></span>
    </button>

    <div x-show="open" 
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[9999]" 
         @click="open = false" 
         style="display: none;">

        <div @click.stop class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[80vh] flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">Dostępni Kierowcy</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-2 overflow-y-auto flex-1">
                <template x-for="driver in drivers" :key="driver.id">
                    <button @click="selectDriver(driver); open = false;"
                        class="w-full text-left p-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center group border-b border-gray-50 last:border-0">
                        <div class="w-10 h-10 rounded-full bg-gray-100 group-hover:bg-emerald-200 flex items-center justify-center text-gray-500 group-hover:text-emerald-700 mr-3 transition-colors">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900" x-text="driver.full_name"></div>
                            <div class="text-xs text-gray-500" x-text="driver.vehicle_details || 'Brak pojazdu'"></div>
                        </div>
                        <i x-show="selectedDriver && selectedDriver.id === driver.id" class="fas fa-check text-emerald-600 ml-auto"></i>
                    </button>
                </template>
                
                <div x-show="drivers.length === 0" class="p-8 text-center text-gray-500">
                    Brak aktywnych kierowców w systemie.
                </div>
            </div>
        </div>
    </div>
</div>
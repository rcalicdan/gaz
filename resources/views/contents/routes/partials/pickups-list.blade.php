<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-clipboard-list text-emerald-600 mr-2"></i>
            Lista Odbiorów
        </h2>
        <div class="flex items-center gap-2">
            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-xs font-bold" x-text="orders.length"></span>
        </div>
    </div>

    <div x-show="orders.length > 0" class="space-y-3 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
        <template x-for="(pickup, index) in orders" :key="pickup.id">
            <div class="p-3 border rounded-lg transition-all hover:shadow-sm relative group bg-white"
                :class="{
                    'border-red-300 bg-red-50': !pickup.has_coordinates && !pickup.isCustom,
                    'border-gray-200': pickup.has_coordinates || pickup.isCustom
                }">
                
                <div class="flex justify-between items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center border border-gray-300" 
                              x-text="index + 1"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-semibold text-gray-900 text-sm truncate" x-text="pickup.client_name"></span>
                            
                            <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded"
                                :class="{
                                    'bg-yellow-100 text-yellow-700': pickup.status === 'scheduled',
                                    'bg-blue-100 text-blue-700': pickup.status === 'in_progress',
                                    'bg-purple-100 text-purple-700': pickup.isCustom
                                }"
                                x-text="pickup.isCustom ? 'WŁASNY' : (pickup.status_label || pickup.status)">
                            </span>
                        </div>

                        <div class="text-xs text-gray-600 mb-1 flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 mr-1.5 flex-shrink-0"></i>
                            <span class="truncate-2-lines" x-text="pickup.address"></span>
                        </div>

                        <div class="flex items-center gap-3 mt-2 text-xs bg-gray-50 p-1.5 rounded">
                            <div class="flex items-center text-gray-700" title="Rodzaj odpadów">
                                <i class="fas fa-recycle text-emerald-500 mr-1.5"></i>
                                <span x-text="pickup.waste_type || 'Nieokreślony'"></span>
                            </div>
                            <div class="flex items-center text-gray-700 font-medium ml-auto" title="Ilość">
                                <i class="fas fa-weight-hanging text-blue-400 mr-1.5"></i>
                                <span x-text="pickup.waste_quantity ? parseFloat(pickup.waste_quantity).toFixed(2) + ' kg' : '-'"></span>
                            </div>
                        </div>

                        <div x-show="!pickup.has_coordinates && !pickup.isCustom" class="mt-2 flex items-center text-xs text-red-600 font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Brak współrzędnych GPS
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="orders.length === 0" class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
        <i class="fas fa-clipboard-check text-3xl mb-2 text-gray-300"></i>
        <p class="text-sm">Brak zaplanowanych odbiorów na ten dzień.</p>
    </div>
</div>
<div x-show="optimizationResult || orders.length > 0" 
     class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 transition-all">
    
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-edit text-emerald-600 mr-2"></i>
            Edycja Ręczna
        </h2>
        
        <span x-show="manualEditMode" class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
            TRYB EDYCJI AKTYWNY
        </span>
    </div>

    <div class="flex flex-wrap gap-3">
        <button @click="toggleManualEdit()"
            :class="manualEditMode ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all shadow-sm flex items-center">
            <i class="fas" :class="manualEditMode ? 'fa-check' : 'fa-pen'"></i>
            <span class="ml-2" x-text="manualEditMode ? 'Zakończ Edycję' : 'Edytuj Kolejność'"></span>
        </button>

        <button x-show="manualEditMode" @click="saveManualChanges()"
            class="px-4 py-2 rounded-lg font-medium text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center transition-all">
            <i class="fas fa-save mr-2"></i> Zapisz Zmiany
        </button>
        
        <button @click="refreshMap()" 
            class="px-4 py-2 rounded-lg font-medium text-sm bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 shadow-sm flex items-center ml-auto">
            <i class="fas fa-sync-alt mr-2"></i> Odśwież Mapę
        </button>
    </div>
    
    <div x-show="manualEditMode" class="mt-4 p-3 bg-amber-50 text-amber-800 text-xs rounded border border-amber-200">
        <i class="fas fa-info-circle mr-1"></i>
        Przeciągnij elementy na liście lub punkty na mapie, aby zmienić kolejność odbiorów.
    </div>
</div>
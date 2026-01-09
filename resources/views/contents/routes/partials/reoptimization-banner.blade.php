<div x-show="routeNeedsReoptimization" 
     x-transition 
     class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded shadow-sm flex items-start justify-between">
    <div class="flex">
        <i class="fas fa-exclamation-triangle text-amber-500 text-xl mr-3 mt-0.5"></i>
        <div>
            <h3 class="font-bold text-amber-800">Wymagana Aktualizacja Trasy</h3>
            <p class="text-sm text-amber-700 mt-1">
                Wykryto <span x-text="newOrdersCount" class="font-bold"></span> nowe odbiory, które nie są uwzględnione w obecnym planie trasy.
                Zalecana ponowna optymalizacja.
            </p>
        </div>
    </div>
    <button @click="optimizeRoutes()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded text-sm font-bold transition-colors">
        Optymalizuj Teraz
    </button>
</div>
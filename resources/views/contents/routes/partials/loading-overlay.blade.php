<div x-show="loading" 
     x-transition.opacity
     class="fixed inset-0 bg-white/80 backdrop-blur-sm z-[100] flex flex-col items-center justify-center">
    
    <div class="relative w-24 h-24 mb-4">
        <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-emerald-600 text-2xl">
            <i class="fas fa-route"></i>
        </div>
    </div>
    
    <h3 class="text-xl font-bold text-gray-800 mb-2">Optymalizacja Trasy</h3>
    <p class="text-gray-500 animate-pulse">Przetwarzanie lokalizacji i obliczanie najkrótszej ścieżki...</p>
</div>
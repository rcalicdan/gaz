<div x-show="optimizationResult" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-stream text-emerald-600 mr-2"></i>
            Szczegóły Przejazdu
        </h2>
        
        <button @click="window.print()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-print"></i>
        </button>
    </div>

    <div class="p-0">
        <div class="flex items-start p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="flex flex-col items-center mr-4">
                <div class="w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center text-xs font-bold z-10">
                    START
                </div>
                <div class="w-0.5 h-full bg-gray-300 my-1"></div>
            </div>
            <div class="pt-1">
                <h4 class="font-bold text-gray-900">Baza (Start)</h4>
                <p class="text-sm text-gray-500">Warszawa, Centrum Logistyczne</p>
                <div class="mt-1 text-xs font-mono text-gray-400">08:00</div>
            </div>
        </div>
        <div class="route-timeline">
            <template x-for="(step, index) in optimizationResult?.route_steps" :key="index">
                <div class="flex items-start p-4 border-b border-gray-100 hover:bg-emerald-50/30 transition-colors">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 border-2 border-emerald-500 flex items-center justify-center text-sm font-bold z-10"
                             x-text="index + 1">
                        </div>
                        <div class="w-0.5 h-full bg-gray-300 my-1" x-show="index !== optimizationResult.route_steps.length - 1"></div>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900" x-text="step.client_name"></h4>
                                <p class="text-sm text-gray-600 mt-0.5" x-text="step.location"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-900" x-text="step.estimated_arrival"></div>
                                <div class="text-xs text-gray-500 mt-1" x-text="step.distance"></div>
                            </div>
                        </div>
                        
                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                             <span class="bg-gray-100 px-2 py-0.5 rounded">
                                <i class="fas fa-trash mr-1"></i> Odbiór odpadów
                             </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-start p-4 bg-gray-50/50">
            <div class="flex flex-col items-center mr-4">
                <div class="w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center text-xs font-bold z-10">
                    META
                </div>
            </div>
            <div class="pt-1">
                <h4 class="font-bold text-gray-900">Baza (Powrót)</h4>
                <div class="mt-1 text-xs font-mono text-gray-400">
                    Szacowany powrót: <span x-text="executiveSummary?.returnTime"></span>
                </div>
            </div>
        </div>
    </div>
</div>
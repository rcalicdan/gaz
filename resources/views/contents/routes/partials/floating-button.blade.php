 <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3" x-show="orders.length > 0">
     <div x-show="!canOptimizeRoute" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-red-600 text-white text-sm py-2 px-4 rounded-lg shadow-lg mb-2 max-w-xs text-right">
         <i class="fas fa-exclamation-circle mr-1"></i>
         Brak współrzędnych dla niektórych odbiorów.
         <br>Uzupełnij dane klientów.
     </div>

     <button @click="optimizeRoutes()" :disabled="loading || !canOptimizeRoute"
         :class="{
             'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-emerald-500/30': canOptimizeRoute &&
                 !loading,
             'bg-gray-500 cursor-not-allowed grayscale': !canOptimizeRoute && !loading,
             'bg-indigo-600 cursor-wait': loading
         }"
         class="group relative flex items-center justify-center h-16 px-6 rounded-full shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-emerald-300">

         <!-- Icon State -->
         <div class="flex items-center text-white text-lg font-bold">
             <template x-if="loading">
                 <i class="fas fa-circle-notch fa-spin text-2xl"></i>
             </template>

             <template x-if="!loading && canOptimizeRoute">
                 <div class="flex items-center">
                     <i class="fas fa-route text-2xl mr-3"></i>
                     <span class="hidden md:inline">Optymalizuj Trasę</span>
                     <span class="md:hidden">Optymalizuj</span>
                 </div>
             </template>

             <template x-if="!loading && !canOptimizeRoute">
                 <div class="flex items-center">
                     <i class="fas fa-ban text-2xl mr-2"></i>
                     <span class="text-sm">Błąd Danych</span>
                 </div>
             </template>
         </div>

         <div class="absolute -top-2 -right-2 h-8 w-8 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white shadow-sm"
             :class="canOptimizeRoute ? 'bg-red-500' : 'bg-gray-700'">
             <span x-text="orders.length"></span>
         </div>
     </button>
 </div>

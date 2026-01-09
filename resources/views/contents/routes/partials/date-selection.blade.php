<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6">
    <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-calendar-alt text-emerald-600 mr-2"></i>
        Data Trasy
    </h2>

    <div class="space-y-4">
        <div class="date-picker-container">
            <input type="date" x-model="selectedDate" @change="onDateChange($event)"
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" />
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                    <span class="font-medium text-gray-700">Wybrany dzień</span>
                </div>
                <div :class="getDateStatusClass()" class="px-2 py-1 rounded-full text-xs font-semibold border">
                    <span x-text="getDateStatusText()"></span>
                </div>
            </div>

            <div class="text-sm font-semibold text-gray-800 mb-2 capitalize" x-text="formattedSelectedDate"></div>

            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-white rounded-lg p-2 border border-gray-200 shadow-sm">
                    <div class="text-lg font-bold text-emerald-600" x-text="orders.length"></div>
                    <div class="text-xs text-gray-500">Planowanych odbiorów</div>
                </div>
                <div class="bg-white rounded-lg p-2 border border-gray-200 shadow-sm">
                    <div class="text-lg font-bold text-blue-600"
                        x-text="orders.reduce((sum, p) => sum + (parseFloat(p.waste_quantity) || 0), 0).toFixed(2) + ' kg'"></div>
                    <div class="text-xs text-gray-500">Łączna waga</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <button @click="selectedDate = getTodayDate(); updateOrders();"
                :class="selectedDate === getTodayDate() ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-white hover:bg-gray-50 text-gray-700 border-gray-300'"
                class="px-3 py-2 text-xs font-medium rounded-md border transition-colors">
                Dzisiaj
            </button>
            <button @click="changeDate(1)" class="px-3 py-2 text-xs font-medium bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-md transition-colors">
                Jutro
            </button>
             <button @click="changeDate(-1)" class="px-3 py-2 text-xs font-medium bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-md transition-colors">
                Wczoraj
            </button>
        </div>
    </div>
</div>
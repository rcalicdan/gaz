<aside
    :class="{
        'translate-x-0': isMobileMenuOpen,
        '-translate-x-full': !isMobileMenuOpen,
        'md:w-64': !isDesktopSidebarCollapsed,
        'md:w-20': isDesktopSidebarCollapsed
    }"
    class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-emerald-50 shadow-lg transform transition-all duration-300 ease-in-out md:translate-x-0">

    <div class="flex flex-col items-center justify-center h-24 border-b border-gray-200 flex-shrink-0">
        <svg class="mx-auto mb-1 text-emerald-700" style="height:2em;width:2em;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2c-2.21 0-4 1.79-4 4 0 3.31 4 6 4 6s4-2.69 4-6c0-2.21-1.79-4-4-4zM6 14c-1.1 1.1-2 3-2 4 0 .55.45 1 1 1h12c.55 0 1-.45 1-1 0-1-1-2.9-2-4-2 1-5 1-10 0z" fill="currentColor"/>
        </svg>
        <span class="block text-2xl font-extrabold text-emerald-800 tracking-tight mt-1">{{ config('app.name') }}</span>
    </div>

    <nav class="flex-grow mt-4 overflow-y-auto">
        <x-dashboard.sidebar-link href="{{ route('dashboard') }}" icon="fas fa-home" :active="request()->routeIs('dashboard')"
            :label="__('Dashboard')" />

        @can('viewAny', App\Models\User::class)
            <x-dashboard.sidebar-link href="{{ route('users.index') }}" icon="fas fa-users" :active="request()->routeIs('users.*')"
                :label="__('Users')" />
        @endcan

        @can('viewAny', App\Models\WasteType::class)
            <x-dashboard.sidebar-link href="{{ route('waste-types.index') }}" icon="fas fa-recycle" :active="request()->routeIs('waste-types.*')"
                :label="__('Waste Types')" />
        @endcan

        @can('viewAny', App\Models\Client::class)
            <x-dashboard.sidebar-link href="{{ route('clients.index') }}" icon="fas fa-building" :active="request()->routeIs('clients.*')"
                :label="__('Clients')" />
        @endcan

        @can('viewAny', App\Models\PriceList::class)
            <x-dashboard.sidebar-link href="{{ route('price-lists.index') }}" icon="fas fa-tags" :active="request()->routeIs('price-lists.*')"
                :label="__('Price Lists')" />
        @endcan

        @can('viewAny', App\Models\Pickup::class)
            <x-dashboard.sidebar-link href="{{ route('pickups.index') }}" icon="fas fa-truck-pickup" :active="request()->routeIs('pickups.*')"
                :label="__('Pickups')" />
        @endcan

        @can('viewAny', App\Models\Route::class)
            <x-dashboard.sidebar-link href="{{ route('routes.index') }}" icon="fas fa-route" :active="request()->routeIs('routes.*')"
                :label="__('Routes')" />
        @endcan
    </nav>

    <div class="hidden md:block p-4 border-t border-gray-200 flex-shrink-0">
        <button @click="isDesktopSidebarCollapsed = !isDesktopSidebarCollapsed"
            class="w-full flex items-center justify-center text-gray-500 hover:text-gray-700 rounded-md p-2">
            <i x-show="!isDesktopSidebarCollapsed" class="fas fa-chevron-left text-xl"></i>
            <i x-show="isDesktopSidebarCollapsed" class="fas fa-chevron-right text-xl"></i>
        </button>
    </div>
    <livewire:livewire-placeholder />
</aside>
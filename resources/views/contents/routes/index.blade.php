<x-layouts.app title="Planowanie Tras - EkoLife">
    <div x-data="routeOptimizer()" x-cloak class="min-h-screen relative">
        <div class="w-full max-w-full pb-24"> 
            
            @include('contents.routes.partials.reoptimization-banner')
            
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 lg:gap-6">
                <div class="xl:col-span-1 space-y-4 lg:space-y-6">
                    @include('contents.routes.partials.date-selection')
                    @include('contents.routes.partials.driver-selection')
                    @include('contents.routes.partials.pickups-list')
                    @include('contents.routes.partials.optimization-controls')
                </div>          

                <div class="xl:col-span-3 space-y-4 lg:space-y-6">
                    @include('contents.routes.partials.map-section')
                    @include('contents.routes.partials.manual-edit-controls')
                    
                    @include('contents.routes.partials.route-summary')
                    @include('contents.routes.partials.route-details')
                </div>
            </div>
        </div>

        @include('contents.routes.partials.loading-overlay')
        @include('contents.routes.partials.floating-button')
    
    </div>
</x-layouts.app>
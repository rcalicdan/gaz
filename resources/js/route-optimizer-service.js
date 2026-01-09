class RouteOptimizerService {
    constructor(component) {
        this.component = component;
        this.depotCoords = [21.0122, 52.2297];
        this.idMapping = new Map();
    }

    canOptimize() {
        const orders = this.component.orders;
        const validOrders = orders.filter(o => o.has_coordinates || o.isCustom);
        return validOrders.length > 0;
    }

    async optimizeRoutes() {
        this.idMapping.clear();
        const payload = this.buildPayload();

        const response = await fetch('/api/vroom/optimize', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': `Bearer ${document.querySelector('meta[name="token"]').content}`
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const err = await response.json();
            throw new Error(err.message || `Błąd optymalizacji: ${response.statusText}`);
        }

        const result = await response.json();
        return this.processResult(result);
    }

    buildPayload() {
        const driverId = this.component.selectedDriver.id;
        
        const jobs = this.component.orders
            .filter(o => o.has_coordinates || o.isCustom)
            .map((o, index) => {
                let coords;
                if (o.isCustom && Array.isArray(o.coordinates)) {
                    coords = [parseFloat(o.coordinates[1]), parseFloat(o.coordinates[0])];
                } else if (o.vroom_coordinates && Array.isArray(o.vroom_coordinates)) {
                    coords = o.vroom_coordinates.map(c => parseFloat(c));
                } else if (Array.isArray(o.coordinates) && o.coordinates.length === 2) {
                    coords = [parseFloat(o.coordinates[1]), parseFloat(o.coordinates[0])];
                } else {
                    console.warn('Invalid coordinates for order:', o);
                    return null;
                }

                const vroomId = typeof o.id === 'string' ? this.stringToId(o.id) : o.id;
                
                this.idMapping.set(vroomId, String(o.id));

                return {
                    id: vroomId,
                    description: String(o.id),
                    location: coords,
                    service: 600,
                    amount: [Math.ceil(parseFloat(o.waste_quantity) || 1)],
                    priority: this.mapPriority(o.priority)
                };
            })
            .filter(job => job !== null);

        if (jobs.length === 0) {
            throw new Error('Brak prawidłowych punktów do optymalizacji');
        }

        return {
            vehicles: [{
                id: driverId,
                profile: 'driving-car',
                start: this.depotCoords,
                end: this.depotCoords,
                capacity: [10000]
            }],
            jobs: jobs,
            options: { g: true }
        };
    }
    
    stringToId(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return Math.abs(hash) % 2147483647;
    }

    mapPriority(priorityString) {
        const priorities = {
            'high': 100,
            'medium': 50,
            'low': 10
        };
        return priorities[priorityString] || 0;
    }

    processResult(vroomResponse) {
        if (!vroomResponse.routes || vroomResponse.routes.length === 0) {
            throw new Error("Nie znaleziono rozwiązania trasy.");
        }

        const route = vroomResponse.routes[0];
        
        const steps = route.steps
            .filter(step => step.type === 'job')
            .map(step => {
                const originalId = this.idMapping.get(step.job) || step.description;
                const originalOrder = this.component.orders.find(o => String(o.id) === originalId);

                if (!originalOrder) {
                    console.warn('Could not find original order for VROOM job:', step.job, originalId);
                    return null;
                }

                return {
                    job: originalId, 
                    client_name: originalOrder.client_name,
                    location: originalOrder.address,
                    coordinates: originalOrder.coordinates,
                    distance: (step.distance / 1000).toFixed(1) + ' km',
                    duration: step.duration,
                    arrival: step.arrival,
                    estimated_arrival: this.secondsToTime(step.arrival),
                    type: step.type
                };
            })
            .filter(step => step !== null);

        return {
            total_distance: (route.distance / 1000).toFixed(2),
            total_time: Math.ceil(route.duration / 60),
            geometry: route.geometry,
            route_steps: steps,
            original_response: vroomResponse
        };
    }

    secondsToTime(seconds) {
        const startOfDay = 28800; 
        const totalSeconds = startOfDay + seconds;
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    }
}

window.RouteOptimizerService = RouteOptimizerService;
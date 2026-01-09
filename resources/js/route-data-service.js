class RouteDataService {
    constructor() {
        this.baseUrl = '/api/route-data';
        this.cache = new Map();
        this.cacheTimeout = 5 * 60 * 1000; 
    }

    getHeaders() {
        const token = document.querySelector('meta[name="token"]')?.content;
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        };
    }

    async request(url, options = {}) {
        const config = {
            headers: this.getHeaders(),
            ...options
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            return data; 
        } catch (error) {
            console.error('API Request Failed:', error);
            throw error;
        }
    }

    async getDrivers() {
        if (this.cache.has('drivers')) return this.cache.get('drivers');

        const response = await this.request(`${this.baseUrl}/drivers`);
        if (response.success) {
            this.cache.set('drivers', response.data);
            return response.data;
        }
        return [];
    }

    async getPickupsForDriverAndDate(driverId, date) {
        const url = `${this.baseUrl}/orders?driver_id=${driverId}&date=${date}`;
        const response = await this.request(url);
        return response.success ? response.data : [];
    }

    async loadSavedRouteOptimization(driverId, date) {
        try {
            const url = `${this.baseUrl}/saved-optimization?driver_id=${driverId}&date=${date}`;
            const response = await this.request(url);
            return response.success ? response.data : null;
        } catch (e) {
            return null;
        }
    }

    async saveOptimization(data) {
        return await this.request(`${this.baseUrl}/save-optimization`, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async triggerGeocoding() {
        return await this.request(`${this.baseUrl}/geocode`, { method: 'POST' });
    }
}

window.RouteDataService = RouteDataService;
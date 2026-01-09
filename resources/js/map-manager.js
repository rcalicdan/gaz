class MapManager {
    constructor(data) {
        this.data = data;
        this.map = null;
        this.markers = [];
        this.routePolyline = null;
        this.depotCoordinates = [52.2297, 21.0122];
        this.editMode = false;

        this.colors = {
            depot: '#1f2937',
            pickup: '#10b981',
            custom: '#8b5cf6',
            warning: '#ef4444'
        };
    }

    init() {
        if (this.map) return;

        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        this.map = L.map('map').setView(this.depotCoordinates, 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);

        this.addDepotMarker();
        this.refreshMarkers();
    }

    addDepotMarker() {
        const icon = L.divIcon({
            html: `<div style="background:${this.colors.depot}; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);">
                    <i class="fas fa-warehouse text-sm"></i>
                   </div>`,
            className: 'custom-div-icon',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        L.marker(this.depotCoordinates, { icon: icon })
            .addTo(this.map)
            .bindPopup('<strong>Baza / Centrum Logistyczne</strong>');
    }

    refreshMarkers() {
        if (!this.map) return;

        this.markers.forEach(m => this.map.removeLayer(m));
        this.markers = [];

        this.data.orders.forEach((pickup, index) => {
            let coords = pickup.coordinates;

            if (!coords || !Array.isArray(coords) || coords.length !== 2) {
                console.warn('Invalid coordinates for pickup:', pickup);
                return;
            }

            const lat = parseFloat(coords[0]);
            const lng = parseFloat(coords[1]);

            if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                console.warn('Out of range coordinates:', lat, lng);
                return;
            }

            const color = pickup.isCustom ? this.colors.custom :
                pickup.isNewOrder ? this.colors.warning :
                    this.colors.pickup;

            const icon = L.divIcon({
                html: `<div style="background:${color}; color:white; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white; font-weight:bold; box-shadow:0 2px 4px rgba(0,0,0,0.2);">
                    ${index + 1}
                   </div>`,
                className: 'custom-div-icon',
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            const marker = L.marker([lat, lng], {
                icon: icon,
                draggable: this.editMode
            }).addTo(this.map);

            const popupContent = `
            <div class="p-1">
                <div class="font-bold text-gray-800">#${index + 1} ${pickup.client_name}</div>
                <div class="text-xs text-gray-500 mb-1">${pickup.address}</div>
                ${pickup.isCustom ? '<div class="text-xs text-purple-600 font-semibold">✓ Punkt Dodatkowy</div>' : ''}
                ${pickup.isNewOrder ? '<div class="text-xs text-red-600 font-semibold">⚠ Nowe Zamówienie</div>' : ''}
                <div class="text-xs font-semibold text-emerald-600">
                    <i class="fas fa-recycle"></i> ${pickup.waste_type} 
                    (${parseFloat(pickup.waste_quantity).toFixed(2)} kg)
                </div>
            </div>
        `;

            marker.bindPopup(popupContent);

            if (this.editMode) {
                marker.on('dragend', (event) => {
                    const position = event.target.getLatLng();
                    pickup.coordinates = [position.lat, position.lng];
                
                    if (this.data.saveManualChanges) {
                        this.data.saveManualChanges(true);
                    }
                });
            }

            this.markers.push(marker);
        });

        this.fitBounds();
    }

    visualizeOptimizedRoute() {
        this.clearRoute();

        if (!this.data.optimizationResult || !this.data.optimizationResult.geometry) return;

        const decoded = this.decodePolyline(this.data.optimizationResult.geometry);

        this.routePolyline = L.polyline(decoded, {
            color: '#059669',
            weight: 5,
            opacity: 0.7,
            lineJoin: 'round'
        }).addTo(this.map);

        this.map.fitBounds(this.routePolyline.getBounds(), { padding: [50, 50] });
    }

    clearRoute() {
        if (this.routePolyline) {
            this.map.removeLayer(this.routePolyline);
            this.routePolyline = null;
        }
    }

    enableManualEdit() {
        this.editMode = true;
        this.refreshMarkers();
        this.map.getContainer().style.cursor = 'crosshair';

        this.map.on('click', (e) => {
            if (this.editMode) {
                if (confirm("Dodać nowy punkt odbioru w tym miejscu?")) {
                    this.data.addCustomStop(e.latlng.lat, e.latlng.lng);
                }
            }
        });
    }

    disableManualEdit() {
        this.editMode = false;
        this.refreshMarkers();
        this.map.getContainer().style.cursor = '';
        this.map.off('click');
    }

    fitBounds() {
        if (this.markers.length === 0) return;

        const group = new L.featureGroup(this.markers);
        this.map.fitBounds(group.getBounds().pad(0.1));
    }

    decodePolyline(str, precision) {
        var index = 0, lat = 0, lng = 0, coordinates = [], shift = 0, result = 0,
            byte = null, latitude_change, longitude_change, factor = Math.pow(10, precision || 5);

        while (index < str.length) {
            byte = null; shift = 0; result = 0;
            do {
                byte = str.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);
            latitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));
            shift = result = 0;
            do {
                byte = str.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);
            longitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));
            lat += latitude_change;
            lng += longitude_change;
            coordinates.push([lat / factor, lng / factor]);
        }
        return coordinates;
    }
}

window.MapManager = MapManager;
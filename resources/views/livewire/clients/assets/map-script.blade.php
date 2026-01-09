@push('scripts')
    <script>
        const __defineClientMap = () => {
            Alpine.data('clientMap', (config) => ({
                map: null,
                marker: null,
                pulseCircle: null,
                lat: config.lat ? parseFloat(config.lat) : null,
                lng: config.lng ? parseFloat(config.lng) : null,
                hasCoords: config.hasCoords,

                init() {
                    this.$nextTick(() => {
                        if (this.map) return;

                        const mapContainer = document.getElementById('map-container');
                        if (!mapContainer) {
                            console.error('Map container not found');
                            return;
                        }

                        // Initialize the Leaflet map only after the Leaflet script is available.
                        const initializeLeafletMap = () => {
                            try {
                                if (mapContainer._leaflet_id) {
                                    mapContainer._leaflet_id = undefined;
                                }

                                const initialLat = this.lat || 52.237049;
                                const initialLng = this.lng || 21.017532;
                                const zoom = this.hasCoords ? 15 : 6;

                                this.map = L.map('map-container', {
                                    zoomControl: true,
                                    scrollWheelZoom: true,
                                    dragging: true,
                                    touchZoom: true,
                                    doubleClickZoom: true,
                                    boxZoom: true,
                                    keyboard: true
                                }).setView([initialLat, initialLng], zoom);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors',
                                    maxZoom: 19
                                }).addTo(this.map);

                                if (this.hasCoords) {
                                    this.addPointyMarker(this.lat, this.lng);
                                }

                                // Ensure we react to coordinate updates
                                Livewire.on('update-map-coordinates', (data) => {
                                    this.updateMap(data[0].lat, data[0].lng);
                                });

                                // React to Livewire events and browser events that request reflow
                                const reflowMap = () => {
                                    setTimeout(() => {
                                        if (this.map) {
                                            this.map.invalidateSize();
                                            if (this.hasCoords) {
                                                this.map.setView([this.lat, this.lng], 15);
                                            }
                                        }
                                    }, 150);
                                };

                                Livewire.on('invalidate-map-size', reflowMap);
                                window.addEventListener('invalidate-map-size', reflowMap);

                        // Also respond to explicit init requests (emitted when the tab is activated)
                        Livewire.on('init-map', () => {
                            if (!this.map) {
                                waitForLeaflet(initializeLeafletMap);
                            } else {
                                reflowMap();
                            }
                        });
                                // force a reflow shortly after initialization as a fallback.
                                // Additionally, observe container visibility and use an interval fallback —
                                // this ensures we call invalidateSize once the tab content becomes visible.

                                const ensureVisibleAndReflow = () => {
                                    const container = mapContainer;
                                    if (!container) return false;

                                    const width = container.offsetWidth;
                                    const height = container.offsetHeight;

                                    if (width > 0 && height > 0) {
                                        if (this.map) {
                                            this.map.invalidateSize();
                                            if (this.hasCoords) {
                                                this.map.setView([this.lat, this.lng], 15);
                                            }
                                        }
                                        return true;
                                    }

                                    return false;
                                };

                                // Try immediately
                                if (!ensureVisibleAndReflow()) {
                                    // Observe mutations on parent in case layout changes (e.g., tab becomes active)
                                    let visibilityObserver = null;

                                    try {
                                        visibilityObserver = new MutationObserver(() => {
                                            if (ensureVisibleAndReflow() && visibilityObserver) {
                                                visibilityObserver.disconnect();
                                                visibilityObserver = null;
                                            }
                                        });

                                        const observeTarget = mapContainer.parentElement || mapContainer;
                                        visibilityObserver.observe(observeTarget, { attributes: true, childList: true, subtree: true });
                                    } catch (e) {
                                        // MutationObserver might not be available in some environments — ignore
                                    }

                                    // Interval fallback for a short period
                                    const intervalId = setInterval(() => {
                                        if (ensureVisibleAndReflow()) {
                                            clearInterval(intervalId);
                                            if (visibilityObserver) {
                                                visibilityObserver.disconnect();
                                            }
                                        }
                                    }, 200);

                                    // Also reflow on window resize
                                    const resizeHandler = () => ensureVisibleAndReflow();
                                    window.addEventListener('resize', resizeHandler);

                                    // Keep references to clean up on destroy
                                    this._mapVisibilityCleanup = () => {
                                        clearInterval(intervalId);
                                        if (visibilityObserver) visibilityObserver.disconnect();
                                        window.removeEventListener('resize', resizeHandler);
                                    };
                                } else {
                                    // Clean state if already visible
                                    this._mapVisibilityCleanup = () => {};
                                }
                            } catch (e) {
                                console.error('Error initializing Leaflet map:', e);
                            }
                        };

                        const waitForLeaflet = (callback, timeout = 3000) => {
                            if (typeof L !== 'undefined') {
                                callback();
                                return;
                            }

                            let waited = 0;
                            const interval = setInterval(() => {
                                if (typeof L !== 'undefined') {
                                    clearInterval(interval);
                                    callback();
                                    return;
                                }

                                waited += 50;
                                if (waited >= timeout) {
                                    clearInterval(interval);
                                    console.error('Leaflet failed to load within timeout, map not initialized');
                                }
                            }, 50);
                        };

                        waitForLeaflet(initializeLeafletMap);
                    });
                },


                destroy() {
                    if (this._mapVisibilityCleanup) {
                        try { this._mapVisibilityCleanup(); } catch (e) {}
                        this._mapVisibilityCleanup = null;
                    }

                    if (this.map) {
                        this.map.remove();
                        this.map = null;
                        this.marker = null;
                        this.pulseCircle = null;
                    }
                },

                addPointyMarker(lat, lng) {
                    lat = parseFloat(lat);
                    lng = parseFloat(lng);

                    if (isNaN(lat) || isNaN(lng)) {
                        console.error('Invalid coordinates:', lat, lng);
                        return;
                    }

                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }
                    if (this.pulseCircle) {
                        this.map.removeLayer(this.pulseCircle);
                    }

                    const markerHtml = `
                        <div class="pulse-marker">
                            <div class="pulse-ring"></div>
                        </div>
                    `;

                    const customIcon = L.divIcon({
                        className: 'custom-pin-marker',
                        html: markerHtml,
                        iconSize: [50, 60],
                        iconAnchor: [25, 55],
                        popupAnchor: [0, -55]
                    });

                    this.marker = L.marker([lat, lng], {
                        icon: customIcon,
                        zIndexOffset: 1000
                    }).addTo(this.map);

                    this.marker.bindPopup(`
                        <div class="text-center p-2">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <strong class="text-gray-900">{{ __('Client Location') }}</strong>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div>{{ __('Latitude') }}: ${lat.toFixed(6)}</div>
                                <div>{{ __('Longitude') }}: ${lng.toFixed(6)}</div>
                            </div>
                        </div>
                    `, {
                        maxWidth: 250,
                        className: 'custom-popup'
                    });

                    setTimeout(() => {
                        this.marker.openPopup();
                    }, 500);
                },

                updateMap(lat, lng) {
                    this.lat = parseFloat(lat);
                    this.lng = parseFloat(lng);
                    this.hasCoords = true;

                    this.addPointyMarker(this.lat, this.lng);

                    this.map.setView([this.lat, this.lng], 15);

                    this.map.invalidateSize();
                }
            }));
        };
        if (window.Alpine && window.Alpine.data) {
            __defineClientMap();
            if (typeof Alpine.initTree === 'function') {
                document.querySelectorAll('[x-data*="clientMap("]').forEach(el => Alpine.initTree(el));
            }
        } else {
            document.addEventListener('alpine:init', __defineClientMap);
        }
    </script>
@endpush

document.addEventListener('alpine:init', () => {
    Alpine.data('routeOptimizer', () => ({
        loading: false,
        dataLoaded: false,
        mapReady: false,

        drivers: [],
        selectedDriver: null,
        selectedDate: new Date().toISOString().slice(0, 10),

        orders: [],
        newOrdersCount: 0,
        routeNeedsReoptimization: false,

        optimizationResult: null,
        optimizationError: null,
        showRouteSummary: false,
        manualEditMode: false,

        dataService: null,
        mapManager: null,
        optimizer: null,

        async init() {
            this.dataService = new RouteDataService();
            this.optimizer = new RouteOptimizerService(this);

            this.$nextTick(async () => {
                this.mapManager = new MapManager(this);
                this.mapManager.init();
                this.mapReady = true;

                await this.loadDrivers();

                this.$watch('selectedDate', () => this.updateOrders());
                this.$watch('selectedDriver', () => this.updateOrders());
            });
        },

        get executiveSummary() {
            if (!this.optimizationResult) return null;
            const startHour = 8;
            const totalSeconds = this.optimizationResult.total_time || 0;
            const endTimeSeconds = (startHour * 3600) + totalSeconds;
            const date = new Date(endTimeSeconds * 1000);
            const returnTime = date.toISOString().substr(11, 5);

            return {
                deliveryDate: this.formattedSelectedDate,
                totalStops: this.orders.length,
                totalDistance: this.formatDistance(this.optimizationResult.total_distance),
                totalTime: this.formatTime(this.optimizationResult.total_time / 60),
                savings: "N/A",
                startTime: '08:00',
                firstDelivery: this.optimizationResult.route_steps?.[0]?.estimated_arrival || '08:15',
                lastDelivery: this.optimizationResult.route_steps?.[this.optimizationResult.route_steps.length - 1]?.estimated_arrival || '16:00',
                returnTime: returnTime
            };
        },

        async loadDrivers() {
            this.loading = true;
            try {
                this.drivers = await this.dataService.getDrivers();
                if (this.drivers.length > 0) {
                    this.selectedDriver = this.drivers[0];
                    if (this.mapReady) {
                        await this.updateOrders();
                    }
                }
                this.dataLoaded = true;
            } catch (e) {
                console.error("Error loading drivers", e);
            } finally {
                this.loading = false;
            }
        },

        async updateOrders(skipSavedRoute = false) {
            if (!this.selectedDriver || !this.selectedDate) return;

            // Wait for map to be ready
            if (!this.mapReady) {
                console.warn('Map not ready yet, skipping updateOrders');
                return;
            }

            this.loading = true;
            this.optimizationResult = null;
            this.routeNeedsReoptimization = false;

            try {
                const apiOrders = await this.dataService.getPickupsForDriverAndDate(
                    this.selectedDriver.id,
                    this.selectedDate
                );

                this.orders = apiOrders;

                if (!skipSavedRoute) {
                    const savedOpt = await this.dataService.loadSavedRouteOptimization(
                        this.selectedDriver.id,
                        this.selectedDate
                    );

                    if (savedOpt) {
                        this.restoreSavedRoute(savedOpt);
                    } else {
                        if (this.mapManager) {
                            this.mapManager.refreshMarkers();
                            this.mapManager.clearRoute();
                        }
                    }
                } else {
                    this.$nextTick(() => {
                        if (this.mapManager) {
                            this.mapManager.refreshMarkers();
                            this.mapManager.clearRoute();
                        }
                    });
                }

            } catch (e) {
                console.error("Error updating orders", e);
            } finally {
                this.loading = false;
            }
        },

        restoreSavedRoute(savedOpt) {
            this.optimizationResult = savedOpt.optimization_result;

            let customStops = [];
            if (savedOpt.manual_modifications?.custom_stops) {
                customStops = savedOpt.manual_modifications.custom_stops.map(stop => {
                    let coords = stop.coordinates;
                    if (!Array.isArray(coords)) {
                        coords = Object.values(coords);
                    }
                    coords = coords.map(c => parseFloat(c));

                    return {
                        ...stop,
                        isCustom: true,
                        status: 'custom',
                        has_coordinates: true,
                        coordinates: coords,
                        vroom_coordinates: [coords[1], coords[0]],
                        waste_quantity: parseFloat(stop.waste_quantity) || 0
                    };
                });
            }

            const editedCoordsMap = new Map();
            if (savedOpt.manual_modifications?.edited_coordinates) {
                savedOpt.manual_modifications.edited_coordinates.forEach(edit => {
                    editedCoordsMap.set(String(edit.id), edit);
                });
            }

            const allAvailablePoints = [...this.orders, ...customStops];
            const pointMap = new Map(allAvailablePoints.map(o => [String(o.id), o]));

            const newSequence = [];
            const savedSequenceIds = savedOpt.pickup_sequence || [];
            const processedIds = new Set();

            savedSequenceIds.forEach(id => {
                const strId = String(id);
                if (pointMap.has(strId) && !processedIds.has(strId)) {
                    let point = pointMap.get(strId);

                    if (editedCoordsMap.has(strId)) {
                        const edit = editedCoordsMap.get(strId);
                        point.coordinates = edit.modified_coordinates;
                        point.vroom_coordinates = edit.vroom_coordinates;
                        point.original_coordinates = edit.original_coordinates;
                        point.manuallyEdited = true;
                    }

                    newSequence.push(point);
                    processedIds.add(strId);
                    pointMap.delete(strId);
                }
            });

            const remaining = Array.from(pointMap.values());
            const newDbOrders = remaining.filter(o => !o.isCustom);

            if (newDbOrders.length > 0) {
                this.newOrdersCount = newDbOrders.length;
                this.routeNeedsReoptimization = true;
                newDbOrders.forEach(o => {
                    o.isNewOrder = true;
                    newSequence.push(o);
                });
            }

            this.orders = newSequence;
            this.showRouteSummary = !!this.optimizationResult;
            this.manualEditMode = savedOpt.is_manual_edit || false;

            this.$nextTick(() => {
                setTimeout(() => {
                    if (this.mapManager) {
                        this.mapManager.refreshMarkers();

                        if (this.optimizationResult?.geometry) {
                            setTimeout(() => {
                                this.mapManager.visualizeOptimizedRoute();
                            }, 150);
                        }
                    }
                }, 100);
            });
        },

        async optimizeRoutes() {
            if (!this.mapManager) {
                alert('Mapa nie jest jeszcze gotowa');
                return;
            }

            this.loading = true;
            this.optimizationError = null;

            try {
                const result = await this.optimizer.optimizeRoutes();
                this.optimizationResult = result;

                this.reorderLocalOrders(result.route_steps);

                this.mapManager.refreshMarkers();
                this.mapManager.visualizeOptimizedRoute();
                this.showRouteSummary = true;
                this.routeNeedsReoptimization = false;
                this.newOrdersCount = 0;

                await this.saveManualChanges(false);

            } catch (e) {
                this.optimizationError = e.message;
                alert("Błąd optymalizacji: " + e.message);
            } finally {
                this.loading = false;
            }
        },

        reorderLocalOrders(steps) {
            const orderMap = new Map(this.orders.map(o => [String(o.id), o]));
            const newOrder = [];

            steps.forEach(step => {
                const order = orderMap.get(String(step.job));
                if (order) newOrder.push(order);
            });

            this.orders = newOrder;
        },

        async saveManualChanges(isManual = true) {
            if (!this.selectedDriver || !this.selectedDate) {
                alert('Wybierz kierowcę i datę');
                return;
            }

            const customStops = this.orders
                .filter(o => o.isCustom)
                .map(o => {
                    let coords = o.coordinates;
                    if (!Array.isArray(coords) || coords.length !== 2) {
                        console.warn('Invalid coordinates for custom stop:', o);
                        return null;
                    }

                    return {
                        id: o.id,
                        client_name: o.client_name || 'Punkt Dodatkowy',
                        address: o.address || 'Nieokreślony adres',
                        coordinates: coords.map(c => parseFloat(c)),
                        waste_type: o.waste_type || 'Inne',
                        waste_quantity: parseFloat(o.waste_quantity) || 0
                    };
                })
                .filter(stop => stop !== null);

            const editedCoordinates = this.orders
                .filter(o => !o.isCustom && o.manuallyEdited)
                .map(o => ({
                    id: o.id,
                    original_coordinates: o.original_coordinates,
                    modified_coordinates: o.coordinates,
                    vroom_coordinates: o.vroom_coordinates || [o.coordinates[1], o.coordinates[0]]
                }));

            const requiresOptimization = isManual && !this.optimizationResult;

            const data = {
                driver_id: this.selectedDriver.id,
                optimization_date: this.selectedDate,
                optimization_result: this.optimizationResult || null,
                order_sequence: this.orders.map(o => o.id),
                total_distance: this.optimizationResult?.total_distance || null,
                total_time: this.optimizationResult?.total_time || null,
                is_manual_edit: isManual,
                manual_modifications: {
                    requires_optimization: requiresOptimization,
                    custom_stops: customStops,
                    edited_coordinates: editedCoordinates,
                    modification_timestamp: new Date().toISOString()
                }
            };

            try {
                await this.dataService.saveOptimization(data);

                if (isManual) {
                    if (requiresOptimization) {
                        alert("Zmiany zapisane. Trasa wymaga optymalizacji.");
                    } else {
                        alert("Zmiany zapisane pomyślnie.");
                    }
                }

                this.routeNeedsReoptimization = requiresOptimization;
            } catch (e) {
                console.error('Save error:', e);
                alert("Błąd zapisu: " + e.message);
            }
        },

        getTodayDate() {
            return new Date().toISOString().slice(0, 10);
        },

        onDateChange(e) {
            this.selectedDate = e.target.value;
        },

        changeDate(days) {
            const d = new Date(this.selectedDate);
            d.setDate(d.getDate() + days);
            this.selectedDate = d.toISOString().slice(0, 10);
        },

        getDateStatusText() {
            const today = this.getTodayDate();
            if (this.selectedDate === today) return "Dzisiaj";
            return this.selectedDate > today ? "Przyszła" : "Archiwum";
        },

        getDateStatusClass() {
            const today = this.getTodayDate();
            if (this.selectedDate === today) return "bg-green-100 text-green-700 border-green-200";
            return "bg-gray-100 text-gray-700 border-gray-200";
        },

        get formattedSelectedDate() {
            return new Date(this.selectedDate).toLocaleDateString('pl-PL', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        },

        get coordinateValidationSummary() {
            const total = this.orders.length;
            const valid = this.orders.filter(o => o.has_coordinates || o.isCustom).length;
            return {
                total: total,
                valid: valid,
                invalid: total - valid,
                missing: this.orders.filter(o => !o.has_coordinates && !o.isCustom).length
            };
        },

        get canOptimizeRoute() {
            return this.coordinateValidationSummary.valid > 0;
        },

        selectDriver(driver) {
            this.selectedDriver = driver;
        },

        toggleManualEdit() {
            if (!this.mapManager) {
                alert('Mapa nie jest jeszcze gotowa');
                return;
            }

            this.manualEditMode = !this.manualEditMode;
            if (this.manualEditMode) {
                this.mapManager.enableManualEdit();
            } else {
                this.mapManager.disableManualEdit();
            }
        },

        refreshMap() {
            this.optimizationResult = null;
            this.showRouteSummary = false;
            this.routeNeedsReoptimization = false;
            this.newOrdersCount = 0;
            this.optimizationError = null;

            if (this.manualEditMode && this.mapManager) {
                this.manualEditMode = false;
                this.mapManager.disableManualEdit();
            }

            if (this.mapManager) {
                this.mapManager.clearRoute();
            }

            this.updateOrders(true);
        },

        async resetOptimization() {
            if (confirm("Czy na pewno chcesz zresetować i usunąć zapisaną trasę?")) {
                this.loading = true;

                try {
                    await this.dataService.deleteSavedOptimization(
                        this.selectedDriver.id,
                        this.selectedDate
                    );
                    this.optimizationResult = null;
                    this.showRouteSummary = false;
                    this.routeNeedsReoptimization = false;
                    this.newOrdersCount = 0;

                    if (this.manualEditMode) {
                        this.manualEditMode = false;
                        if (this.mapManager) {
                            this.mapManager.disableManualEdit();
                        }
                    }

                    if (this.mapManager) {
                        this.mapManager.clearRoute();
                    }

                    await this.updateOrders(true);

                } catch (e) {
                    console.error('Error resetting optimization:', e);
                    alert('Błąd podczas resetowania trasy: ' + e.message);
                } finally {
                    this.loading = false;
                }
            }
        },

        formatDistance(km) {
            if (!km) return '0 km';
            return parseFloat(km).toFixed(1) + ' km';
        },

        formatTime(minutes) {
            if (!minutes) return '0h 0m';
            const h = Math.floor(minutes / 60);
            const m = Math.round(minutes % 60);
            return `${h}h ${m}m`;
        },

        onDragStart(index, event) {
            if (!this.manualEditMode) {
                event.preventDefault();
                return;
            }
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', index);
            event.dataTransfer.setDragImage(event.target, 0, 0);
        },

        onDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        },

        onDrop(index, event) {
            event.preventDefault();
            if (!this.manualEditMode) return;

            const fromIndex = parseInt(event.dataTransfer.getData('text/plain'));
            if (isNaN(fromIndex) || fromIndex === index) return;

            // Reorder orders array
            const element = this.orders.splice(fromIndex, 1)[0];
            this.orders.splice(index, 0, element);

            // Jeśli mamy wynik optymalizacji, trzeba zsynchronizować kolejność route_steps
            if (this.optimizationResult && this.optimizationResult.route_steps) {
                // Mapuj orders na route_steps bazując na job ID
                const jobToStep = new Map(
                    this.optimizationResult.route_steps.map(step =>
                        [String(step.job), step]
                    )
                );

                const newSteps = [];
                this.orders.forEach(order => {
                    const step = jobToStep.get(String(order.id));
                    if (step) {
                        newSteps.push(step);
                    }
                });

                // Tylko jeśli znaleźliśmy wszystkie kroki, zaaplikuj nową kolejność
                if (newSteps.length === this.optimizationResult.route_steps.length) {
                    this.optimizationResult.route_steps = newSteps;
                }
            }

            if (this.mapManager) {
                this.mapManager.clearRoute();
            }

            // Wymuś ponowne renderowanie
            this.$nextTick(() => {
                this.routeNeedsReoptimization = true;
            });
        },

        addCustomStop(lat, lng) {
            if (isNaN(lat) || isNaN(lng)) {
                alert('Nieprawidłowe współrzędne');
                return;
            }

            const newStop = {
                id: 'custom-' + Date.now(),
                client_name: 'Punkt Dodatkowy',
                address: `Współrzędne: ${lat.toFixed(5)}, ${lng.toFixed(5)}`,
                coordinates: [parseFloat(lat), parseFloat(lng)],
                status: 'custom',
                priority: 'medium',
                waste_type: 'Inne',
                waste_quantity: 0,
                isCustom: true,
                has_coordinates: true
            };

            this.orders.push(newStop);
            this.routeNeedsReoptimization = true;

            if (this.mapManager) {
                this.mapManager.refreshMarkers();
            }
        }
    }));
});

<x-admin-app-layout :title="'Events List'">
    <div class="container mx-auto px-4 py-8">

        <!-- Event Header -->
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $event->name }}</h1>
                <p class="text-gray-600">{{ $event->start_date }} • {{ $event->venue->name }}</p>
            </div>
            <div class="flex gap-4 text-sm">
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-gray-300"></span> Available
                </div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-green-500"></span> Selected
                </div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-red-500"></span> Taken</div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 h-[600px]">

            <!-- CANVAS AREA (Left) -->
            <div class="flex-1 bg-gray-100 border rounded-lg relative overflow-hidden group">
                <div id="seat-map-container" class="w-full h-full"></div>

                <!-- Zoom Controls -->
                <div class="absolute bottom-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition">
                    <button onclick="zoomStage(1.2)" class="bg-white p-2 rounded shadow hover:bg-gray-50">+</button>
                    <button onclick="zoomStage(0.8)" class="bg-white p-2 rounded shadow hover:bg-gray-50">-</button>
                </div>

                <!-- Loading Overlay -->
                <div id="loading-overlay"
                    class="absolute inset-0 bg-white/80 flex items-center justify-center z-50 hidden">
                    <span class="animate-spin text-2xl">⏳</span>
                </div>
            </div>

            <!-- CART SIDEBAR (Right) -->
            <div class="w-full lg:w-80 bg-white border rounded-lg p-6 flex flex-col">
                <h3 class="font-bold text-lg mb-4">Your Selection</h3>

                <div id="cart-items" class="flex-1 overflow-y-auto space-y-3 mb-4">
                    <!-- Items injected via JS or Blade loop -->
                    @forelse($myLocks as $lock)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded"
                            id="cart-item-{{ $lock->seat_id }}">
                            <div>
                                <div class="font-bold">{{ $lock->seat->section->name }}</div>
                                <div class="text-sm text-gray-500">Row {{ $lock->seat->row_label }} Seat
                                    {{ $lock->seat->seat_number }}</div>
                            </div>
                            <button onclick="removeSeat({{ $lock->seat_id }})"
                                class="text-red-500 hover:text-red-700">×</button>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center mt-10" id="empty-cart-msg">No seats selected</p>
                    @endforelse
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between font-bold text-lg mb-4">
                        <span>Total</span>
                        <span id="cart-total">$0.00</span>
                    </div>
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 rounded font-bold hover:bg-indigo-700 transition">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/konva@9.2.0/konva.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // --- 1. INITIALIZE DATA FROM BLADE ---
        const designJson = @json($designJson);
        const soldSeats = @json($soldSeats); // Array of Int [101, 102]
        const lockedSeats = @json($lockedSeats); // Array of Int [105, 106]
        let mySeats = @json($myLocks->pluck('seat_id')); // Array of Int [201]

        const EVENT_ID = {{ $event->id }};
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // --- 2. SETUP KONVA STAGE ---
        const container = document.getElementById('seat-map-container');
        const stage = new Konva.Stage({
            container: 'seat-map-container',
            width: container.offsetWidth,
            height: container.offsetHeight,
            draggable: true,
            scale: {
                x: 0.8,
                y: 0.8
            } // Start zoomed out slightly
        });

        const layer = new Konva.Layer();
        stage.add(layer);

        // --- 3. LOAD THE DESIGN ---
        if (designJson) {
            // We parse the design JSON and add it to the layer
            // NOTE: Admin saves the WHOLE stage, so we extract nodes
            const rawNode = Konva.Node.create(designJson);

            // We need to extract children from the saved layer and move to our new layer
            // Or if you saved just children, iterate them.
            // Assuming Admin saved `stage.toJSON()`:
            if (rawNode.children && rawNode.children[0]) {
                const savedLayer = rawNode.children[0];
                savedLayer.children.forEach(child => {
                    // Clone needed because moving removes from old parent
                    const clone = child.clone();
                    layer.add(clone);
                });
            }
        }

        // --- 4. APPLY LOGIC (COLORS & CLICKS) ---

        // Find all visual circles representing seats
        // Assuming you named them 'seat_shape' in the Admin Designer
        const seatShapes = layer.find('.seat_shape');

        seatShapes.forEach(seat => {
            // We need to match this Visual Circle to the Database ID.
            // In the Admin Designer, we saved { row: 'A', num: 1, section: 'VIP' } in attributes.
            // BUT, we need the actual DB ID.
            // Ideally, your API returns a map: { "A-1": 504, "A-2": 505 }
            // For simplicity, let's assume the Admin JSON saved 'seatId' attribute if you updated it,
            // OR we match by label logic.

            // *CRITICAL*: In a real app, you should fetch a "Manifest" from API that maps
            // Section+Row+Number -> Database ID.
            // For this demo, we assume the seat visual contains `seatId` or we simply rely on visual blocking.
            // If your visual JSON doesn't have IDs, we need to fetch the mapping.

            // Let's assume for this example we match visually,
            // but normally you'd pass a mapped array from controller.

            // RESET STYLE
            seat.stroke(null);
            seat.strokeWidth(0);

            // CHECK STATUS
            // Note: You need a way to link visual seat to DB ID.
            // If missing, you might match by `label` string: "A-1"
            const seatLabel = seat.getAttr('seatRow') + '-' + seat.getAttr('seatNum');

            // ** Here we need the mapping passed from Controller **
            // Let's assume `seatMap` is available: { "A-1": 101, "A-2": 102 }
            // For now, let's pretend seat visual has an ID or we skip ID check for demo visuals.

            // LOGIC:
            // 1. If Sold -> Grey, Not clickable
            // 2. If Locked -> Red, Not clickable
            // 3. If MySeat -> Green
            // 4. Else -> Default Color (Blue/White)

            // Events
            seat.on('mouseenter', function() {
                stage.container().style.cursor = 'pointer';
                this.stroke('black');
                this.strokeWidth(2);
            });

            seat.on('mouseleave', function() {
                stage.container().style.cursor = 'default';
                this.stroke(null);
                this.strokeWidth(0);
            });

            seat.on('click tap', function() {
                handleSeatClick(this);
            });
        });

        layer.batchDraw();


        // --- 5. INTERACTION LOGIC ---

        function handleSeatClick(seatNode) {
            const row = seatNode.getAttr('seatRow');
            const num = seatNode.getAttr('seatNum');
            const label = `${row}-${num}`; // "A-1"

            // Show loading
            document.getElementById('loading-overlay').classList.remove('hidden');

            // We need to find the REAL ID from the DB to lock it.
            // In a real app, you'd look up `label` in a JS object passed from Blade.
            // Example: const seatDbId = seatMapping[label];
            // For this snippet, I'll mock the ID or you must ensure your Admin
            // saves the ID into the JSON after creation.

            // MOCK CALL:
            axios.post('/api/lock-seat', {
                    event_id: EVENT_ID,
                    // We are sending label to finding ID backend side if ID is missing in JSON
                    seat_label: label,
                    section_name: seatNode.getParent().getAttr('sectionName')
                }, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                })
                .then(response => {
                    if (response.data.status === 'locked') {
                        // Success: Turn Green
                        seatNode.fill('#22c55e'); // Green-500
                        addToCartUI(response.data.seat);
                    } else if (response.data.status === 'unlocked') {
                        // Unselected: Turn back to default
                        seatNode.fill('#cbd5e1'); // Slate-300
                        removeFromCartUI(response.data.seat.id);
                    }
                    layer.batchDraw();
                })
                .catch(error => {
                    alert(error.response?.data?.message || 'Seat unavailable');
                })
                .finally(() => {
                    document.getElementById('loading-overlay').classList.add('hidden');
                });
        }

        // --- 6. UI HELPERS ---

        function addToCartUI(seat) {
            const list = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('empty-cart-msg');
            if (emptyMsg) emptyMsg.remove();

            const html = `
            <div class="flex justify-between items-center bg-gray-50 p-3 rounded" id="cart-item-${seat.id}">
                <div>
                    <div class="font-bold">${seat.section_name}</div>
                    <div class="text-sm text-gray-500">Row ${seat.row_label} Seat ${seat.seat_number}</div>
                </div>
                <button class="text-red-500">✓</button>
            </div>
        `;
            list.insertAdjacentHTML('beforeend', html);
        }

        function removeFromCartUI(id) {
            const item = document.getElementById(`cart-item-${id}`);
            if (item) item.remove();
        }

        function zoomStage(scaleBy) {
            const oldScale = stage.scaleX();
            const newScale = oldScale * scaleBy;
            stage.scale({
                x: newScale,
                y: newScale
            });
        }

        // Resize handling
        window.addEventListener('resize', () => {
            stage.width(container.offsetWidth);
            stage.height(container.offsetHeight);
        });
    </script>
</x-admin-app-layout>

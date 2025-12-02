<!-- ===========================
     SEAT SELECTOR MODAL
=========================== -->
<div class="modal fade" id="seatSelectorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Select Your Seats</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0 d-flex">

                <!-- LEFT: SEAT MAP -->
                <div class="flex-grow-1 bg-light position-relative">

                    <div class="px-3 py-2 shadow-sm bg-white small">
                        <strong>Tips:</strong>
                        Drag to move • Scroll to zoom • Click seats to select
                    </div>

                    <div id="seatMapContainer" style="width: 100%; height: calc(100vh - 120px);"></div>
                </div>

                <!-- RIGHT: TICKET TYPE + SELECTED SEATS -->
                <div style="width: 380px;" class="border-start bg-white p-3">

                    <h5 class="fw-bold mb-3">Choose Ticket Type</h5>

                    <select id="ticketTypeSelector" class="form-select mb-4">
                        <option value="">Select Ticket Type</option>
                        @foreach ($ticketTypes as $ticket)
                            <option value="{{ $ticket->id }}">
                                {{ $ticket->name }} — ৳{{ number_format($ticket->price, 2) }}
                            </option>
                        @endforeach
                    </select>

                    <h5 class="fw-bold mb-3">Selected Seats</h5>

                    <div id="selectedSeatsList" class="border p-2 rounded bg-light small"
                        style="max-height: 300px; overflow-y: auto;">
                        <p class="text-muted m-0">No seats selected.</p>
                    </div>

                    <button id="addToCartBtn" class="btn btn-primary w-100 mt-4" disabled>
                        Add to Cart
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- BUTTON (already included in event page) -->
{{-- <button id="openSeatSelectorButton" class="main-btn w-100" data-bs-toggle="modal" data-bs-target="#seatSelectorModal">Select Seats</button> --}}

@push('scripts')
    <!-- Konva JS -->
    <script src="https://cdn.jsdelivr.net/npm/konva@9.3.3/konva.min.js"></script>

    <script>
        /* ==========================================================
       GLOBAL STATE
    ========================================================== */
        let stage, layer;
        window.SEAT_SHAPES = {};
        window.userSelectedSeats = new Set();
        window.seatStatusMap = @json($seatStatuses);

        const EVENT_ID = "{{ $event->id }}";
        const DESIGN_JSON = @json($designJson);

        /* ==========================================================
           INITIALIZE MAP WHEN MODAL OPENS
        ========================================================== */
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("openSeatSelectorButton")
                ?.addEventListener("click", initSeatMap);
        });

        /* ==========================================================
           INIT SEAT MAP
        ========================================================== */
        function initSeatMap() {
            const container = document.getElementById("seatMapContainer");
            if (!container) return;

            container.innerHTML = "";

            stage = new Konva.Stage({
                container: "seatMapContainer",
                width: container.offsetWidth,
                height: container.offsetHeight,
                draggable: true
            });

            layer = new Konva.Layer();
            stage.add(layer);

            addZoomHandlers();
            renderMapFromJSON();
        }

        /* ==========================================================
           RENDER MAP FROM DESIGN JSON
        ========================================================== */
        function renderMapFromJSON() {
            if (!Array.isArray(DESIGN_JSON)) {
                console.error("Invalid design_json");
                return;
            }

            DESIGN_JSON.forEach(node => {
                switch (node.type) {
                    case "SECTION":
                        drawSection(node);
                        break;
                    case "SEAT":
                        drawSeat(node);
                        break;
                    case "LABEL":
                        drawLabel(node);
                        break;
                }
            });

            layer.draw();
        }

        /* ==========================================================
           DRAW SECTION
        ========================================================== */
        function drawSection(sec) {
            layer.add(new Konva.Rect({
                x: sec.x,
                y: sec.y,
                width: sec.width,
                height: sec.height,
                fill: 'rgba(0,0,0,0.04)',
                stroke: '#555',
                strokeWidth: 1,
                cornerRadius: 4
            }));

            layer.add(new Konva.Text({
                x: sec.x,
                y: sec.y - 18,
                text: sec.name ?? "Section",
                fontSize: 14
            }));
        }

        /* ==========================================================
           DRAW LABEL
        ========================================================== */
        function drawLabel(node) {
            layer.add(new Konva.Text({
                x: node.x,
                y: node.y,
                text: node.text || "",
                fontSize: node.fontSize || 18,
                fontStyle: "bold",
                fill: node.color || "#333"
            }));
        }

        /* ==========================================================
           DRAW SEAT
        ========================================================== */
        function drawSeat(node) {

            const id = node.db_id;
            if (!id) return;

            const status = seatStatusMap[id] || "available";

            const seat = new Konva.Circle({
                x: node.x,
                y: node.y,
                radius: 10,
                fill: seatColor(status),
                stroke: "#222",
                strokeWidth: 1,
                opacity: status === "locked" ? 0.6 : 1
            });

            window.SEAT_SHAPES[id] = seat;

            seat.on("mouseover", () => showTooltip(seat, node.label));
            seat.on("mouseout", hideTooltip);

            seat.on("click", () => onSeatClick(id, seat));

            layer.add(seat);
        }

        /* ==========================================================
           SEAT COLOR BASED ON STATUS
        ========================================================== */
        function seatColor(status) {
            return {
                available: "#2ecc71",
                locked: "#f1c40f",
                sold: "#e74c3c"
            } [status] || "#bdc3c7";
        }

        /* ==========================================================
           HANDLE SEAT CLICK
        ========================================================== */
        function onSeatClick(id, shape) {
            const status = seatStatusMap[id];

            if (status === "sold") return;
            if (status === "locked" && !userSelectedSeats.has(id)) return;

            if (userSelectedSeats.has(id)) {
                shape.fill(seatColor("available"));
                unselectSeat(id);
                unlockSeat(id);
            } else {
                shape.fill("#3498db");
                selectSeat(id);
                lockSeat(id);
            }

            shape.draw();
        }

        /* ==========================================================
           MARK SELECTED / UNSELECTED
        ========================================================== */
        function selectSeat(id) {
            userSelectedSeats.add(id);
            refreshSelectedSeatList();
        }

        function unselectSeat(id) {
            userSelectedSeats.delete(id);
            refreshSelectedSeatList();
        }

        /* ==========================================================
           UPDATE SELECTED SEAT SIDEBAR
        ========================================================== */
        function refreshSelectedSeatList() {
            const box = document.getElementById("selectedSeatsList");
            const btn = document.getElementById("addToCartBtn");

            if (userSelectedSeats.size === 0) {
                box.innerHTML = `<p class="text-muted m-0">No seats selected.</p>`;
                btn.disabled = true;
                return;
            }

            btn.disabled = false;

            let html = "";
            userSelectedSeats.forEach(id => {
                html += `<div class="p-2 bg-white border mb-2 rounded">Seat ID: ${id}</div>`;
            });

            box.innerHTML = html;
        }

        /* ==========================================================
           LOCK SEAT ON SERVER
        ========================================================== */
        function lockSeat(id) {
            fetch("{{ route('frontend.seat.lock', $event->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    seat_id: id
                })
            });
        }

        /* ==========================================================
           UNLOCK SEAT
        ========================================================== */
        function unlockSeat(id) {
            fetch("{{ route('frontend.seat.unlock', $event->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    seat_id: id
                })
            });
        }

        /* ==========================================================
           ZOOM HANDLER
        ========================================================== */
        function addZoomHandlers() {
            let scaleFactor = 1.06;

            stage.on("wheel", e => {
                e.evt.preventDefault();

                let oldScale = stage.scaleX();
                let pointer = stage.getPointerPosition();
                let mousePoint = {
                    x: pointer.x / oldScale - stage.x() / oldScale,
                    y: pointer.y / oldScale - stage.y() / oldScale
                };

                let newScale = e.evt.deltaY > 0 ? oldScale / scaleFactor : oldScale * scaleFactor;

                stage.scale({
                    x: newScale,
                    y: newScale
                });

                let newPos = {
                    x: -(mousePoint.x - pointer.x / newScale) * newScale,
                    y: -(mousePoint.y - pointer.y / newScale) * newScale
                };

                stage.position(newPos);
                stage.batchDraw();
            });
        }

        /* ==========================================================
           TOOLTIP
        ========================================================== */
        let tooltipLayer = new Konva.Layer();
        let tooltip = new Konva.Label({
            visible: false,
            opacity: 0.75
        });

        tooltip.add(new Konva.Tag({
            fill: "black",
            pointerDirection: "down",
            pointerWidth: 10,
            pointerHeight: 10,
        }));

        tooltip.add(new Konva.Text({
            text: "",
            fontSize: 14,
            padding: 5,
            fill: "white"
        }));

        tooltipLayer.add(tooltip);

        document.addEventListener("DOMContentLoaded", () => {
            stage?.add(tooltipLayer);
        });

        function showTooltip(shape, text) {
            tooltip.visible(true);
            tooltip.position({
                x: shape.x(),
                y: shape.y() - 22
            });
            tooltip.getText().text(text || "Seat");
            tooltipLayer.batchDraw();
        }

        function hideTooltip() {
            tooltip.visible(false);
            tooltipLayer.batchDraw();
        }

        /* ==========================================================
           ADD TO CART
        ========================================================== */
        document.getElementById("addToCartBtn").addEventListener("click", () => {
            const ticketType = document.getElementById("ticketTypeSelector").value;

            if (!ticketType) {
                alert("Select a ticket type first.");
                return;
            }

            userSelectedSeats.forEach(id => {
                fetch("{{ route('frontend.cart.add', $event->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        seat_id: id,
                        ticket_type: ticketType
                    })
                });
            });

            window.location.href = "{{ route('frontend.cart') }}";
        });
    </script>
@endpush

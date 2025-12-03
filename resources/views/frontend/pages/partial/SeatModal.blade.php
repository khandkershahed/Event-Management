<div class="modal fade" id="seatSelectorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header border-0 shadow-sm" style="z-index:1050; background:#fff;">
                <div class="d-flex align-items-center gap-4">
                    <h5 class="modal-title fw-bold">Select Seats</h5>
                    <div class="d-flex gap-3 small">
                        <div class="d-flex align-items-center"><span class="badge rounded-circle bg-success me-1" style="width:10px;height:10px;"></span> Available</div>
                        <div class="d-flex align-items-center"><span class="badge rounded-circle bg-secondary me-1" style="width:10px;height:10px;"></span> Sold/Locked</div>
                        <div class="d-flex align-items-center"><span class="badge rounded-circle bg-primary me-1" style="width:10px;height:10px;"></span> Selected</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0 position-relative" style="background: #f8fafc; overflow: hidden;">

                <div id="seatMapViewport" style="width:100%; height:100%; cursor:grab; touch-action:none;">
                    <div id="seatMapCanvas" style="position:absolute; top:0; left:0; transform-origin:0 0;"></div>
                </div>

                <div id="seat-popover" class="card shadow-lg position-absolute" style="display:none; width:240px; z-index:2000; border:none;">
                    <div class="card-header bg-dark text-white py-2 d-flex justify-content-between">
                        <span id="pop-title" class="fw-bold small">Seat</span>
                        <button type="button" class="btn-close btn-close-white btn-sm" onclick="closePopover()"></button>
                    </div>
                    <div class="card-body p-2 bg-white">
                        <div id="pop-tickets" class="d-grid gap-2"></div>
                    </div>
                </div>

                <div id="seat-tooltip" style="position:fixed; display:none; background:rgba(0,0,0,0.8); color:#fff; padding:4px 8px; border-radius:4px; font-size:12px; pointer-events:none; z-index:9999;"></div>
            </div>

            <div class="modal-footer justify-content-between bg-white border-top">
                <div>
                    <small class="text-muted d-block">Selected Seats</small>
                    <div id="ui-selected-count" class="fw-bold text-primary" style="font-size:1.1rem;">0</div>
                </div>
                <a href="{{ route('frontend.cart') }}" class="btn btn-primary px-5 fw-bold">
                    Go to Checkout <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    #seatMapViewport {
        background-image: linear-gradient(#e4e6ef 1px, transparent 1px), linear-gradient(90deg, #e4e6ef 1px, transparent 1px);
        background-size: 20px 20px;
    }
    .sp-item {
        position: absolute; border: 1px solid #3b5fff; background: rgba(59, 95, 255, 0.06);
        border-radius: 6px; box-sizing: border-box;
    }
    .sp-item h6 {
        margin: 0; padding: 4px; background: #eef1ff; border-bottom: 1px solid #d0d6ff;
        text-align: center; font-size: 11px; font-weight:700; color:#333; pointer-events: none;
        overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
    }
    /* Special Types */
    .sp-stage { border-color: #ff9800; background: rgba(255, 153, 0, 0.10); }
    .sp-stage h6 { background: #fff4e0; border-bottom-color: #ffcc80; }
    .sp-ga { border-color: #28a745; background: rgba(40, 167, 69, 0.10); }
    .sp-ga h6 { background: #e3f9e5; border-bottom-color: #a3cfbb; }
    .sp-table { border-color: #8e44ad; background: rgba(142, 68, 173, 0.10); border-radius: 50%; }
    .sp-table h6 { display: none; }

    /* Seat Nodes */
    .sp-seat {
        position: absolute; width: 32px; height: 32px; border-radius: 50%;
        background: #28a745; color: #fff; font-size: 9px; font-weight: 600;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: transform 0.1s; white-space: nowrap; overflow: hidden;
    }
    .sp-seat:hover { transform: scale(1.15); z-index: 100; border: 2px solid #fff; }

    /* Status Colors */
    .sp-seat.sold { background: #dcdcdc !important; color: #999; cursor: default; pointer-events: none; }
    .sp-seat.locked { background: #95a5a6 !important; cursor: default; pointer-events: none; }
    .sp-seat.selected { background: #007bff !important; box-shadow: 0 0 0 2px #fff, 0 0 5px #007bff; }

    /* Popover Arrow */
    #seat-popover::after {
        content: ''; position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%);
        border-width: 6px 6px 0; border-style: solid; border-color: #fff transparent transparent transparent;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- DATA INJECTION ---
    const rawDesign = {!! json_encode($designJson) !!};
    // Ensure we handle both raw array and wrapped object formats
    const designItems = Array.isArray(rawDesign) ? rawDesign : (rawDesign?.sections || []);

    const dbSections = @json($sections); // [{id:21, name:"VIP"}, ...]
    const dbSeats = @json($seats);       // [{id:647, label:"A1", section_id:21}, ...]
    const seatStatus = @json($seatStatuses); // {647: "available", ...}
    const ticketTypes = @json($ticketTypes); // [{id:1, name:"VIP", price:120, valid_section_ids:["21"]}, ...]

    // --- STATE ---
    let scale = 1, panX = 0, panY = 0, isPanning = false, startX = 0, startY = 0;
    let selectedCount = 0;

    // --- DOM ELEMENTS ---
    const canvas = document.getElementById('seatMapCanvas');
    const viewport = document.getElementById('seatMapViewport');
    const popover = document.getElementById('seat-popover');
    const tooltip = document.getElementById('seat-tooltip');
    const countDisplay = document.getElementById('ui-selected-count');
    const btnOpen = document.getElementById('openSeatSelectorButton');

    // Initialize if modal button exists
    if(btnOpen) {
        btnOpen.addEventListener('click', () => {
            setTimeout(() => {
                // Center map logic could go here
            }, 200);
        });
    }

    // --- 1. RENDER MAP ---
    function initMap() {
        canvas.innerHTML = ''; // Clear

        if(!designItems || designItems.length === 0) {
            canvas.innerHTML = '<div class="p-5 text-center text-muted">No layout found for this event.</div>';
            return;
        }

        designItems.forEach(item => {
            // Create Section Box
            const el = document.createElement('div');
            el.classList.add('sp-item');
            if(item.type === 'stage') el.classList.add('sp-stage');
            if(item.type === 'general_admission') el.classList.add('sp-ga');
            if(item.type === 'table') el.classList.add('sp-table');

            el.style.left = item.x + 'px';
            el.style.top = item.y + 'px';
            el.style.width = item.width + 'px';
            el.style.height = item.height + 'px';
            el.style.transform = `rotate(${item.rotation || 0}deg)`;

            // Label
            if(item.type !== 'table') {
                const h6 = document.createElement('h6');
                h6.innerText = item.name;
                el.appendChild(h6);
            } else {
                // Tables often have name in middle
                el.title = item.name;
            }

            // --- SEATS ---
            if(item.seats && item.seats.length > 0) {
                // 1. Find Database Section ID using Name Match
                const dbSec = dbSections.find(s => s.name === item.name);
                const dbSecId = dbSec ? dbSec.id : null;

                item.seats.forEach(visualSeat => {
                    if(visualSeat.dead) return; // Skip hidden seats

                    const seatEl = document.createElement('div');
                    seatEl.classList.add('sp-seat');
                    seatEl.style.left = visualSeat.x + 'px';
                    seatEl.style.top = visualSeat.y + 'px';
                    seatEl.innerText = visualSeat.label;

                    // 2. Find Database Seat ID
                    // We match Visual Label + DB Section ID
                    const dbSeat = dbSeats.find(s => s.label === visualSeat.label && s.section_id === dbSecId);

                    if(dbSeat) {
                        const sId = dbSeat.id;
                        const status = seatStatus[sId] || 'available';

                        if(status === 'sold') seatEl.classList.add('sold');
                        if(status === 'locked') seatEl.classList.add('locked');
                        if(status === 'selected') {
                            seatEl.classList.add('selected');
                            selectedCount++;
                        }

                        // Events
                        if(status === 'available' || status === 'selected') {
                            seatEl.addEventListener('click', (e) => {
                                e.stopPropagation();
                                handleSeatClick(seatEl, dbSeat, item.name, dbSecId);
                            });

                            // Tooltip
                            seatEl.addEventListener('mouseenter', (e) => showTooltip(e, item.name, visualSeat.label));
                            seatEl.addEventListener('mouseleave', hideTooltip);
                        }
                    } else {
                        // Visual only (orphaned seat?)
                        seatEl.classList.add('sold'); // Disable interaction
                    }

                    el.appendChild(seatEl);
                });
            }

            canvas.appendChild(el);
        });
        updateCounter();
    }

    // --- 2. INTERACTION: CLICK SEAT ---
    function handleSeatClick(el, dbSeat, sectionName, sectionId) {
        // If already selected, unselect (unlock)
        if(el.classList.contains('selected')) {
            unlockSeat(dbSeat.id, el);
            return;
        }

        // Find tickets valid for this section
        // Note: ticket.valid_section_ids is array of strings ["21", "22"]
        const validTickets = ticketTypes.filter(t => {
            if(!t.valid_section_ids) return false;
            // Convert both to string to be safe
            return t.valid_section_ids.includes(String(sectionId));
        });

        if(validTickets.length === 0) {
            alert('No tickets configured for this section.');
            return;
        }

        // Show Popover
        showPopover(el, dbSeat, sectionName, validTickets);
    }

    // --- 3. POPOVER ---
    function showPopover(el, dbSeat, secName, tickets) {
        const rect = el.getBoundingClientRect();
        const popX = rect.left + (rect.width/2) - 110; // Center 220px popover
        const popY = rect.top - 10; // Above seat

        // Adjust for scroll if modal scrolls
        // (Since modal-fullscreen uses fixed logic usually, clientRect is good)
        popover.style.left = popX + 'px';
        popover.style.top = (popY - popover.offsetHeight) + 'px';
        popover.style.display = 'block';

        document.getElementById('pop-title').innerText = `${secName} - ${dbSeat.label}`;
        const container = document.getElementById('pop-tickets');
        container.innerHTML = '';

        tickets.forEach(t => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-outline-dark d-flex justify-content-between align-items-center';
            btn.innerHTML = `<span>${t.name}</span> <strong>$${t.price}</strong>`;
            btn.onclick = () => {
                lockSeat(dbSeat.id, t.id, el);
                closePopover();
            };
            container.appendChild(btn);
        });
    }

    window.closePopover = function() { popover.style.display = 'none'; }

    // --- 4. BACKEND CALLS ---
    function lockSeat(seatId, ticketId, el) {
        // UI Optimistic
        el.classList.add('selected');
        selectedCount++;
        updateCounter();

        fetch(`{{ url('/events/' . $event->id . '/cart/add') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ seat_id: seatId, ticket_type: ticketId })
        })
        .then(r => r.json())
        .then(res => {
            if(res.status !== 'success') {
                alert(res.message);
                el.classList.remove('selected');
                selectedCount--;
                updateCounter();
            }
        });
    }

    function unlockSeat(seatId, el) {
        el.classList.remove('selected');
        selectedCount--;
        updateCounter();

        fetch(`{{ url('/events/' . $event->id . '/cart/remove') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ seat_id: seatId })
        });
    }

    function updateCounter() {
        countDisplay.innerText = selectedCount > 0 ? `${selectedCount} Selected` : 'None';
    }

    // --- 5. UTILS: TOOLTIP & ZOOM ---
    function showTooltip(e, sec, lbl) {
        tooltip.innerText = `${sec} - ${lbl}`;
        tooltip.style.display = 'block';
        tooltip.style.left = (e.clientX + 10) + 'px';
        tooltip.style.top = (e.clientY + 10) + 'px';
    }
    function hideTooltip() { tooltip.style.display = 'none'; }

    viewport.addEventListener('wheel', e => {
        e.preventDefault();
        scale += e.deltaY * -0.001;
        scale = Math.min(Math.max(.4, scale), 3);
        canvas.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
        closePopover();
    });

    viewport.addEventListener('mousedown', e => {
        isPanning = true; startX = e.clientX - panX; startY = e.clientY - panY;
        viewport.style.cursor = 'grabbing';
        closePopover();
    });

    window.addEventListener('mousemove', e => {
        if(!isPanning) return;
        e.preventDefault();
        panX = e.clientX - startX; panY = e.clientY - startY;
        canvas.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
    });

    window.addEventListener('mouseup', () => { isPanning = false; viewport.style.cursor = 'grab'; });

    // Run
    initMap();
});
</script>
@endpush

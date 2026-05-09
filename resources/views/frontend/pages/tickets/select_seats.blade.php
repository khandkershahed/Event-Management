<x-frontend-app-layout>
    <style>
        .public-seat-map-shell{position:relative;width:100%;min-height:620px;border:1px solid #dbe3ef;border-radius:14px;background-color:#f8fafc;background-image:linear-gradient(#e5ecf5 1px,transparent 1px),linear-gradient(90deg,#e5ecf5 1px,transparent 1px);background-size:20px 20px;overflow:auto;padding:16px}.public-seat-stage,.public-seat-section{position:absolute;border-radius:10px;border:1px solid #93c5fd;background:rgba(219,234,254,.78);box-shadow:0 10px 24px rgba(15,23,42,.08)}.public-seat-stage{border-color:#fb923c;background:rgba(255,237,213,.86)}.public-seat-ga{border-color:#22c55e;background:rgba(220,252,231,.78)}.public-seat-table{border-color:#a855f7;background:rgba(243,232,255,.78);border-radius:28px}.public-seat-title{font-size:12px;font-weight:700;text-align:center;padding:5px;border-bottom:1px solid rgba(15,23,42,.12);background:rgba(255,255,255,.65);border-radius:10px 10px 0 0}.public-seat-capacity{font-size:11px;text-align:center;color:#475569;padding-top:8px}.public-seat-btn{position:absolute;border-radius:999px;border:2px solid #16a34a;background:#22c55e;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;min-width:30px;min-height:30px;cursor:pointer;transition:.12s ease-in-out}.public-seat-btn:hover,.public-seat-btn:focus{outline:3px solid rgba(59,130,246,.35)}.public-seat-btn.selected{background:#0ea5e9;border-color:#0284c7}.public-seat-btn.locked{background:#f59e0b;border-color:#d97706;cursor:not-allowed}.public-seat-btn.sold{background:#ef4444;border-color:#dc2626;cursor:not-allowed}.public-seat-btn.unavailable,.public-seat-btn.ticket-blocked{background:#94a3b8;border-color:#64748b;cursor:not-allowed}.seat-map-legend span{display:inline-flex;align-items:center;gap:6px;margin-right:14px;margin-bottom:8px}.seat-map-dot{width:14px;height:14px;border-radius:999px;display:inline-block}.seat-map-dot.available{background:#22c55e}.seat-map-dot.selected{background:#0ea5e9}.seat-map-dot.locked{background:#f59e0b}.seat-map-dot.sold{background:#ef4444}.seat-map-dot.unavailable{background:#94a3b8}.selected-seat-list{list-style:none;margin:0;padding:0}.selected-seat-list li{display:flex;align-items:center;justify-content:space-between;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;margin-bottom:8px}.seat-status-note{font-size:12px}.fallback-seat-btn.selected{background:#0ea5e9;color:#fff;border-color:#0284c7}.fallback-seat-btn.locked{background:#f59e0b;color:#fff;border-color:#d97706}.fallback-seat-btn.sold{background:#ef4444;color:#fff;border-color:#dc2626}.fallback-seat-btn.unavailable,.fallback-seat-btn.ticket-blocked{background:#94a3b8;color:#fff;border-color:#64748b}@media(max-width:767px){.public-seat-map-shell{min-height:720px}.public-seat-section,.public-seat-stage{transform:scale(.86);transform-origin:top left}}
    </style>

    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home &gt; </a></li>
                <li class="breadcrumb-item"><a href="{{ route('event.details', $event->slug) }}">{{ $event->name }} &gt; </a></li>
                <li class="breadcrumb-item active">Select Seats</li>
            </ol>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="main-card p-4">
                        <h2 class="fw-bold mb-2">Choose Your Seat</h2>
                        <p class="text-muted">Pick seats directly from the visual seating map. Your selected seats are held for 10 minutes and can be cancelled before checkout.</p>

                        @if ($tickets->isEmpty())
                            <div class="alert alert-warning">No public tickets are currently available for seat selection.</div>
                        @else
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="ticket-select">Ticket Type</label>
                                <select id="ticket-select" class="form-select">
                                    @foreach ($tickets as $availableTicket)
                                        <option value="{{ $availableTicket->id }}" @selected(($ticket && (int) $ticket->id === (int) $availableTicket->id) || (! $ticket && $loop->first))>
                                            {{ $availableTicket->name }} — {{ $availableTicket->formattedPrice() }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">The map automatically disables seats that are not valid for the selected ticket type.</small>
                            </div>
                        @endif

                        <div class="seat-map-legend mb-3" aria-label="Seat map legend">
                            <span><i class="seat-map-dot available"></i> Available</span>
                            <span><i class="seat-map-dot selected"></i> Selected by you</span>
                            <span><i class="seat-map-dot locked"></i> Locked</span>
                            <span><i class="seat-map-dot sold"></i> Sold</span>
                            <span><i class="seat-map-dot unavailable"></i> Unavailable</span>
                        </div>

                        @if (($seatMap['has_visual_map'] ?? false) && ! empty($seatMap['blocks']))
                            <div id="visual-seat-map" class="public-seat-map-shell" aria-label="Visual seat map">
                                @foreach ($seatMap['blocks'] as $block)
                                    @php
                                        $type = $block['type'] ?? 'seat';
                                        $blockClass = 'public-seat-section';

                                        if ($type === 'stage') {
                                            $blockClass = 'public-seat-stage';
                                        } elseif ($type === 'general_admission') {
                                            $blockClass = 'public-seat-section public-seat-ga';
                                        } elseif ($type === 'table') {
                                            $blockClass = 'public-seat-section public-seat-table';
                                        }

                                        $blockStyle = 'left:'.(int) ($block['x'] ?? 0).'px;top:'.(int) ($block['y'] ?? 0).'px;width:'.(int) ($block['width'] ?? 160).'px;height:'.(int) ($block['height'] ?? 100).'px;transform:rotate('.(int) ($block['rotation'] ?? 0).'deg);';
                                    @endphp

                                    <div class="{{ $blockClass }}" style="{{ $blockStyle }}" data-block-type="{{ $type }}" data-section-id="{{ $block['section_id'] ?? '' }}">
                                        <div class="public-seat-title">{{ $block['name'] ?? 'Section' }}</div>

                                        @if ($type === 'stage')
                                            <div class="public-seat-capacity">Stage / non-selectable area</div>
                                        @elseif (empty($block['seats']))
                                            <div class="public-seat-capacity">{{ (int) ($block['capacity'] ?? 0) }} capacity</div>
                                        @else
                                            @foreach ($block['seats'] as $seat)
                                                @php
                                                    $status = $seat['status'] ?? 'unavailable';
                                                    $isClickable = (bool) ($seat['clickable'] ?? false);
                                                    $allowedTicketIds = implode(',', $seat['allowed_ticket_ids'] ?? []);
                                                @endphp

                                                <button
                                                    type="button"
                                                    class="public-seat-btn {{ $status }}"
                                                    style="left:{{ (int) ($seat['x'] ?? 0) }}px;top:{{ (int) ($seat['y'] ?? 0) }}px"
                                                    data-seat-id="{{ $seat['id'] }}"
                                                    data-seat-label="{{ $seat['label'] }}"
                                                    data-section-name="{{ $block['name'] ?? 'Section' }}"
                                                    data-status="{{ $status }}"
                                                    data-base-status="{{ $seat['base_status'] ?? $status }}"
                                                    data-ticket-ids="{{ $allowedTicketIds }}"
                                                    aria-label="Seat {{ $seat['label'] }} is {{ $status }}"
                                                    {{ $isClickable ? '' : 'disabled' }}
                                                >{{ $seat['label'] }}</button>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div id="fallback-seat-list" data-fallback="1">
                                @foreach (($seatMap['fallback_sections'] ?? []) as $section)
                                    <div class="border rounded p-3 mb-4">
                                        <h5 class="fw-bold mb-3">{{ $section['name'] }}</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            @forelse (($section['seats'] ?? []) as $seat)
                                                @php
                                                    $status = $seat['status'] ?? 'unavailable';
                                                    $isClickable = (bool) ($seat['clickable'] ?? false);
                                                    $allowedTicketIds = implode(',', $seat['allowed_ticket_ids'] ?? []);
                                                @endphp

                                                <button
                                                    type="button"
                                                    class="btn btn-sm fallback-seat-btn seat-lock-btn {{ $status }} {{ $status === 'available' ? 'btn-outline-success' : '' }} {{ $status === 'selected' ? 'btn-primary' : '' }}"
                                                    data-seat-id="{{ $seat['id'] }}"
                                                    data-seat-label="{{ $seat['label'] }}"
                                                    data-section-name="{{ $section['name'] }}"
                                                    data-status="{{ $status }}"
                                                    data-base-status="{{ $seat['base_status'] ?? $status }}"
                                                    data-ticket-ids="{{ $allowedTicketIds }}"
                                                    {{ $isClickable ? '' : 'disabled' }}
                                                >{{ $seat['label'] }}</button>
                                            @empty
                                                <span class="text-muted">No selectable seats in this section.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="main-card p-4 sticky-top">
                        <h4>Your Seat Reservation</h4>
                        <p class="text-muted">Click an available seat to lock it. Click your selected seat again to cancel that lock.</p>
                        <div id="seat-message" class="alert alert-info">No seat selected yet.</div>

                        <h6 class="fw-bold mt-3">Selected Seats</h6>
                        <ul id="selected-seat-list" class="selected-seat-list">
                            @forelse (($seatMap['selected_seats'] ?? []) as $selectedSeat)
                                <li data-selected-seat-id="{{ $selectedSeat['id'] }}">
                                    <span>{{ $selectedSeat['section_name'] }} — {{ $selectedSeat['label'] }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger selected-seat-remove" data-seat-id="{{ $selectedSeat['id'] }}">Remove</button>
                                </li>
                            @empty
                                <li id="selected-seat-empty" class="text-muted">No selected seats.</li>
                            @endforelse
                        </ul>

                        <button type="button" id="clear-selected-seats" class="btn btn-outline-danger w-100 mb-2" @if (empty($seatMap['selected_seats'] ?? [])) disabled @endif>Remove All Selected Seats</button>
                        <a href="{{ route('frontend.cart') }}" class="main-btn btn-hover w-100">Go to Cart</a>
                        <div class="seat-status-note text-muted mt-3">Sold, locked, unavailable, or ticket-restricted seats cannot be selected.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const lockUrl = @json(route('frontend.seats.lock', $event->slug));
            const unlockUrl = @json(route('frontend.seats.unlock', $event->slug));
            const csrfToken = @json(csrf_token());
            const messageBox = document.getElementById('seat-message');
            const selectedSeatList = document.getElementById('selected-seat-list');
            const clearSelectedButton = document.getElementById('clear-selected-seats');

            function showMessage(type, message) {
                if (!messageBox) {
                    return;
                }

                messageBox.className = 'alert alert-' + type;
                messageBox.innerText = message;
            }

            function seatButtons() {
                return document.querySelectorAll('.public-seat-btn, .seat-lock-btn');
            }

            function parseTicketIds(button) {
                return (button.dataset.ticketIds || '')
                    .split(',')
                    .map((id) => id.trim())
                    .filter((id) => id.length > 0);
            }

            function selectedTicketId() {
                const ticketSelect = document.getElementById('ticket-select');
                return ticketSelect ? ticketSelect.value : null;
            }

            function setButtonStatus(button, status) {
                button.dataset.status = status;
                button.classList.remove('available', 'selected', 'locked', 'sold', 'unavailable', 'ticket-blocked', 'btn-outline-success', 'btn-primary', 'btn-secondary');
                button.classList.add(status);

                if (status === 'available') {
                    button.classList.add('btn-outline-success');
                    button.disabled = false;
                } else if (status === 'selected') {
                    button.classList.add('btn-primary');
                    button.disabled = false;
                } else {
                    button.classList.add('btn-secondary');
                    button.disabled = true;
                }
            }

            function applyTicketFilter() {
                const ticketId = selectedTicketId();

                seatButtons().forEach((button) => {
                    const baseStatus = button.dataset.baseStatus || button.dataset.status || 'unavailable';
                    const allowedIds = parseTicketIds(button);

                    if (baseStatus === 'selected') {
                        setButtonStatus(button, 'selected');
                        return;
                    }

                    if (baseStatus !== 'available') {
                        setButtonStatus(button, baseStatus);
                        return;
                    }

                    if (ticketId && allowedIds.length > 0 && !allowedIds.includes(ticketId)) {
                        setButtonStatus(button, 'ticket-blocked');
                        button.dataset.status = 'unavailable';
                        button.setAttribute('aria-label', 'Seat ' + (button.dataset.seatLabel || '') + ' is unavailable for this ticket type');
                        return;
                    }

                    setButtonStatus(button, 'available');
                });
            }

            function refreshSelectedListState() {
                if (!selectedSeatList || !clearSelectedButton) {
                    return;
                }

                const selectedItems = selectedSeatList.querySelectorAll('li[data-selected-seat-id]');
                const emptyItem = document.getElementById('selected-seat-empty');

                clearSelectedButton.disabled = selectedItems.length === 0;

                if (emptyItem) {
                    emptyItem.style.display = selectedItems.length === 0 ? '' : 'none';
                } else if (selectedItems.length === 0) {
                    const li = document.createElement('li');
                    li.id = 'selected-seat-empty';
                    li.className = 'text-muted';
                    li.textContent = 'No selected seats.';
                    selectedSeatList.appendChild(li);
                }
            }

            function addSelectedSeat(button) {
                if (!selectedSeatList || selectedSeatList.querySelector('[data-selected-seat-id="' + button.dataset.seatId + '"]')) {
                    refreshSelectedListState();
                    return;
                }

                const emptyItem = document.getElementById('selected-seat-empty');
                if (emptyItem) {
                    emptyItem.remove();
                }

                const li = document.createElement('li');
                li.dataset.selectedSeatId = button.dataset.seatId;
                li.innerHTML = '<span>' + (button.dataset.sectionName || 'Section') + ' — ' + (button.dataset.seatLabel || button.textContent.trim()) + '</span><button type="button" class="btn btn-sm btn-outline-danger selected-seat-remove" data-seat-id="' + button.dataset.seatId + '">Remove</button>';
                selectedSeatList.appendChild(li);
                refreshSelectedListState();
            }

            function removeSelectedSeat(seatId) {
                const item = selectedSeatList ? selectedSeatList.querySelector('[data-selected-seat-id="' + seatId + '"]') : null;
                if (item) {
                    item.remove();
                }
                refreshSelectedListState();
            }

            seatButtons().forEach((button) => {
                button.addEventListener('click', function () {
                    const status = button.dataset.status;

                    if (status === 'selected') {
                        unlockSeat(button);
                        return;
                    }

                    if (status !== 'available') {
                        showMessage('warning', 'This seat is not available for the selected ticket.');
                        return;
                    }

                    lockSeat(button);
                });
            });

            const ticketSelect = document.getElementById('ticket-select');
            if (ticketSelect) {
                ticketSelect.addEventListener('change', applyTicketFilter);
            }

            if (selectedSeatList) {
                selectedSeatList.addEventListener('click', function (event) {
                    const button = event.target.closest('.selected-seat-remove');
                    if (!button) {
                        return;
                    }

                    const seatButton = document.querySelector('[data-seat-id="' + button.dataset.seatId + '"]');
                    if (seatButton) {
                        unlockSeat(seatButton);
                    }
                });
            }

            if (clearSelectedButton) {
                clearSelectedButton.addEventListener('click', function () {
                    const selectedButtons = Array.from(seatButtons()).filter((button) => button.dataset.status === 'selected');

                    if (selectedButtons.length === 0) {
                        showMessage('info', 'No selected seats to remove.');
                        return;
                    }

                    selectedButtons.forEach((button) => unlockSeat(button));
                });
            }

            function lockSeat(button) {
                const ticketId = selectedTicketId();

                if (!ticketId) {
                    showMessage('danger', 'Please select a ticket type first.');
                    return;
                }

                button.disabled = true;
                showMessage('info', 'Locking seat...');

                fetch(lockUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ event_ticket_id: ticketId, seat_id: button.dataset.seatId })
                })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) {
                            applyTicketFilter();
                            showMessage('danger', data.message || 'Unable to lock this seat.');
                            return;
                        }

                        button.dataset.baseStatus = 'selected';
                        setButtonStatus(button, 'selected');
                        addSelectedSeat(button);
                        showMessage('success', data.message || 'Seat locked and added to cart. Click it again to cancel.');
                    })
                    .catch(() => {
                        applyTicketFilter();
                        showMessage('danger', 'Unable to lock this seat. Please try again.');
                    });
            }

            function unlockSeat(button) {
                button.disabled = true;
                showMessage('info', 'Cancelling seat lock...');

                fetch(unlockUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ seat_id: button.dataset.seatId })
                })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) {
                            button.disabled = false;
                            showMessage('danger', data.message || 'Unable to cancel this seat.');
                            return;
                        }

                        button.dataset.baseStatus = 'available';
                        setButtonStatus(button, 'available');
                        removeSelectedSeat(button.dataset.seatId);
                        applyTicketFilter();
                        showMessage('success', data.message || 'Seat lock cancelled.');
                    })
                    .catch(() => {
                        button.disabled = false;
                        showMessage('danger', 'Unable to cancel this seat. Please try again.');
                    });
            }

            applyTicketFilter();
            refreshSelectedListState();
        </script>
    @endpush
</x-frontend-app-layout>

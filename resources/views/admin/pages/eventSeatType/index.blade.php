@php
    /**
     * admin-seatmap-designer.blade.php
     * Complete Blade file for Admin Seat Map Designer
     */
@endphp

<x-admin-app-layout :title="'Event Seat Map Designer'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-title">Seat Map Designer</div>
            <div class="card-toolbar">
                <button id="add-new-section" class="btn btn-light-primary btn-sm">Add Section</button>
                <button id="add-stage" class="btn btn-light-secondary btn-sm">Add Stage</button>
            </div>
        </div>

        <div class="card-body pt-0">

            {{-- designer area --}}
            <style>
                #seating-plan-designer-container {
                    position: relative;
                    width: 100%;
                    height: 600px;
                    border: 1px solid #ccc;
                    background: #f9f9f9;
                    overflow: hidden;
                }

                .seating-plan-section,
                .seating-plan-stage {
                    position: absolute;
                    border: 1px solid #333;
                    background: rgba(200, 200, 255, 0.3);
                    overflow: hidden;
                    padding: 4px;
                    cursor: move;
                    box-sizing: border-box;
                }

                .seating-plan-stage {
                    background: rgba(255, 230, 180, 0.6);
                }

                .seating-plan-section h6,
                .seating-plan-stage h6 {
                    margin: 0;
                    font-size: 12px;
                    text-align: center;
                    pointer-events: none;
                    user-select: none;
                }

                .ui-rotatable-handle {
                    width: 16px;
                    height: 16px;
                    background: #666;
                    border-radius: 50%;
                    position: absolute;
                    right: -8px;
                    top: 50%;
                    transform: translateY(-50%);
                    cursor: grab;
                    z-index: 9999;
                }

                /* Seat styles */
                .section-seat {
                    position: absolute;
                    width: 18px;
                    height: 18px;
                    background: #28a745;
                    border-radius: 50%;
                    color: #fff;
                    font-size: 10px;
                    text-align: center;
                    line-height: 18px;
                    cursor: pointer;
                    user-select: none;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
                }

                .section-seat.selected {
                    background: #007bff;
                }

                .section-seat .seat-label {
                    pointer-events: none;
                }

                /* context menu styles */
                .context-menu-list {
                    position: absolute;
                    display: none;
                    z-index: 10000;
                    background: white;
                    border: 1px solid #aaa;
                    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
                    list-style: none;
                    padding: 0;
                    min-width: 160px;
                }

                .context-menu-list li {
                    padding: 8px 12px;
                    cursor: pointer;
                }

                .context-menu-list li:hover {
                    background: #eee;
                }

                /* small helper for the controls */
                .designer-controls {
                    display: flex;
                    gap: 8px;
                    align-items: center
                }
            </style>

            <form method="POST" action="{{ route('admin.seatmap.save') }}">
                @csrf

                <div id="seating-plan-designer-container"></div>

                <input type="hidden" id="venue_seating_plan_design" name="seatmap_design">

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Save Seat Map</button>
                    <button type="button" id="clear-designer" class="btn btn-outline-danger">Clear</button>
                </div>
            </form>

            {{-- Context Menus --}}
            <ul id="context-menu-section" class="context-menu-list">
                <li data-action="add-seat">Add seat</li>
                <li data-action="manage-seats">Manage section seats</li>
                <li data-action="change-name">Change section name</li>
                <li data-action="change-bgcolor">Change background color</li>
                <li data-action="duplicate-section">Duplicate section</li>
                <li data-action="delete-section">Delete section</li>
            </ul>

            <ul id="context-menu-stage" class="context-menu-list">
                <li data-action="add-seat">Add seat</li>
                <li data-action="change-stage-name">Change stage name</li>
                <li data-action="change-stage-bgcolor">Change stage background color</li>
            </ul>

            {{-- Modals --}}
            {{-- Change Name Modal --}}
            <div class="modal fade" id="modal-change-name" tabindex="-1" aria-labelledby="modal-change-name-label"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-light">
                            <h5 class="modal-title" id="modal-change-name-label">Change Name</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="input-new-name" class="form-label">New Name</label>
                                <input type="text" class="form-control" id="input-new-name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="save-new-name">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Change Background Color Modal --}}
            <div class="modal fade" id="modal-change-bgcolor" tabindex="-1"
                aria-labelledby="modal-change-bgcolor-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-light">
                            <h5 class="modal-title" id="modal-change-bgcolor-label">Change Background Color</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="input-bgcolor" class="form-label">Background Color (hex/rgb)</label>
                                <input type="text" class="form-control" id="input-bgcolor" placeholder="#ccccff">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="save-bgcolor">Save</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(function() {
                const container = $("#seating-plan-designer-container");
                let sectionCounter = 0;
                let currentContextTarget = null;

                // Utility: create unique ids
                function uid(prefix = 'id') {
                    return prefix + '-' + Math.random().toString(36).substr(2, 9);
                }

                // Create section/stage element and wire behaviors
                function makeSectionElement(id, label = 'Section', x = 10, y = 10, w = 150, h = 100, rotation = 0,
                    isStage = false, seats = []) {
                    const cls = isStage ? 'seating-plan-stage' : 'seating-plan-section';

                    const el = $(`
              <div class="${cls}" data-id="${id}" data-label="${label}" style="left:${x}px; top:${y}px; width:${w}px; height:${h}px; transform:rotate(${rotation}deg);">
                <h6>${label}</h6>
                <div class="ui-rotatable-handle"></div>
              </div>
            `);

                    container.append(el);

                    // make draggable & resizable
                    el.draggable({
                        containment: container,
                        stop: function() {
                            serializeDesign();
                        }
                    }).resizable({
                        containment: container,
                        handles: "n, e, s, w, ne, se, sw, nw",
                        stop: function() {
                            // ensure seats stay inside bounds
                            keepSeatsInBounds(el);
                            serializeDesign();
                        }
                    });

                    // rotation handle
                    el.find('.ui-rotatable-handle').on('mousedown', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const parent = el;
                        const startX = e.pageX;
                        const startRot = getRotation(parent);
                        $(document).on('mousemove.rot', function(me) {
                            const dx = me.pageX - startX;
                            const newRot = startRot + dx * 0.5;
                            parent.css('transform', `rotate(${newRot}deg)`);
                        });
                        $(document).on('mouseup.rot', function() {
                            $(document).off('mousemove.rot mouseup.rot');
                            serializeDesign();
                        });
                    });

                    // contextmenu event
                    el.on('contextmenu', function(e) {
                        e.preventDefault();
                        showContextMenu($(this), isStage, e.pageX, e.pageY);
                    });

                    // clicking inside a section should deselect seats
                    el.on('click', function(e) {
                        // prevent bubbling to container
                        e.stopPropagation();
                        // deselect seats unless clicking a seat
                        if (!$(e.target).hasClass('section-seat')) {
                            el.find('.section-seat').removeClass('selected');
                        }
                    });

                    // double-click inside section to add seat
                    el.on('dblclick', function(e) {
                        const offset = el.offset();
                        const xPos = e.pageX - offset.left - 9; // center
                        const yPos = e.pageY - offset.top - 9;
                        addSeatToSection(el, xPos, yPos);
                    });

                    // load provided seats
                    if (Array.isArray(seats) && seats.length) {
                        seats.forEach(s => {
                            // ensure seat position stays within width/height
                            const sx = Math.max(0, Math.min(s.x, w - 18));
                            const sy = Math.max(0, Math.min(s.y, h - 18));
                            addSeatToSection(el, sx, sy, s.label || s.id, s.id);
                        });
                    }

                    return el;
                }

                function getRotation(el) {
                    const tf = el.css('transform');
                    if (tf && tf !== 'none') {
                        const values = tf.split('(')[1].split(')')[0].split(',');
                        const a = parseFloat(values[0]),
                            b = parseFloat(values[1]);
                        return Math.round(Math.atan2(b, a) * 180 / Math.PI);
                    }
                    return 0;
                }

                // Keep seats inside section after resize
                function keepSeatsInBounds(sectionEl) {
                    sectionEl.find('.section-seat').each(function() {
                        const seat = $(this);
                        const sx = parseFloat(seat.css('left'));
                        const sy = parseFloat(seat.css('top'));
                        const sw = sectionEl.width();
                        const sh = sectionEl.height();
                        const seatW = seat.outerWidth();
                        const seatH = seat.outerHeight();
                        let changed = false;
                        let nx = sx,
                            ny = sy;
                        if (sx > sw - seatW) {
                            nx = Math.max(0, sw - seatW);
                            changed = true;
                        }
                        if (sy > sh - seatH) {
                            ny = Math.max(0, sh - seatH);
                            changed = true;
                        }
                        if (nx < 0) {
                            nx = 0;
                            changed = true;
                        }
                        if (ny < 0) {
                            ny = 0;
                            changed = true;
                        }
                        if (changed) seat.css({
                            left: nx + 'px',
                            top: ny + 'px'
                        });
                    });
                }

                // Add a seat inside a section element
                function addSeatToSection(sectionEl, x, y, label = null, customId = null) {
                    const seats = sectionEl.find('.section-seat');
                    const seatIndex = seats.length + 1;
                    const seatLabel = label || ('S' + seatIndex);
                    const seatId = customId || uid('seat');

                    const seat = $(
                        `<div class="section-seat" data-id="${seatId}" data-label="${seatLabel}" style="left:${x}px; top:${y}px;">
                            <span class="seat-label">${seatLabel}</span>
                        </div>`
                    );

                    // make seat draggable inside section
                    seat.draggable({
                        containment: sectionEl,
                        stop: function() {
                            serializeDesign();
                        }
                    });

                    // click to toggle selection
                    seat.on('click', function(e) {
                        e.stopPropagation();
                        $(this).toggleClass('selected');
                    });

                    // double-click to remove seat
                    seat.on('dblclick', function(e) {
                        e.stopPropagation();
                        if (confirm('Delete this seat?')) {
                            $(this).remove();
                            serializeDesign();
                        }
                    });

                    sectionEl.append(seat);
                    serializeDesign();
                    return seat;
                }

                function serializeDesign() {
                    const design = [];
                    container.find('.seating-plan-section, .seating-plan-stage').each(function() {
                        const el = $(this);
                        const id = el.data('id') || uid('section');
                        el.attr('data-id', id);
                        const label = el.data('label') || el.find('h6').text();
                        const pos = el.position();
                        const w = el.width(),
                            h = el.height();
                        const rotation = getRotation(el);

                        // gather seats of this section
                        const seats = [];
                        el.find('.section-seat').each(function() {
                            const s = $(this);
                            seats.push({
                                id: s.data('id') || uid('seat'),
                                label: s.data('label') || s.find('.seat-label').text(),
                                x: parseFloat(s.css('left')),
                                y: parseFloat(s.css('top'))
                            });
                        });

                        design.push({
                            id,
                            label,
                            x: pos.left,
                            y: pos.top,
                            width: w,
                            height: h,
                            rotation,
                            seats
                        });

                    });

                    $('#venue_seating_plan_design').val(JSON.stringify(design));
                }

                function hideAllContextMenus() {
                    $('.context-menu-list').hide();
                }

                function showContextMenu(el, isStage, x, y) {
                    hideAllContextMenus();
                    currentContextTarget = el;
                    const menu = isStage ? $('#context-menu-stage') : $('#context-menu-section');
                    // ensure menu remains inside viewport
                    const containerOffset = container.offset();
                    const maxLeft = containerOffset.left + container.outerWidth() - 10;
                    const maxTop = containerOffset.top + container.outerHeight() - 10;
                    const finalLeft = Math.min(x, maxLeft);
                    const finalTop = Math.min(y, maxTop);
                    menu.css({
                        top: finalTop + 'px',
                        left: finalLeft + 'px'
                    }).show();
                }

                // close context menu when clicking elsewhere
                $(document).on('click', function() {
                    hideAllContextMenus();
                });

                // handle context menu clicks
                $('.context-menu-list li').on('click', function(e) {
                    e.stopPropagation();
                    hideAllContextMenus();
                    const action = $(this).data('action');
                    if (!currentContextTarget) return;

                    if (action === 'change-name' || action === 'change-stage-name') {
                        const currentName = currentContextTarget.data('label') || currentContextTarget.find(
                            'h6').text();
                        $('#input-new-name').val(currentName);
                        $('#modal-change-name').modal('show');
                    } else if (action === 'manage-seats') {
                        // for compatibility - highlight seats
                        currentContextTarget.find('.section-seat').toggleClass('selected');
                    } else if (action === 'change-bgcolor' || action === 'change-stage-bgcolor') {
                        const bg = rgb2hex(currentContextTarget.css('background-color')) || '';
                        $('#input-bgcolor').val(bg);
                        $('#modal-change-bgcolor').modal('show');
                    } else if (action === 'delete-section') {
                        if (confirm('Delete this section/stage?')) {
                            currentContextTarget.remove();
                            serializeDesign();
                        }
                    } else if (action === 'duplicate-section') {
                        // create clone with new id and same seats
                        const clone = currentContextTarget.clone();
                        const newId = uid('section');
                        clone.attr('data-id', newId);
                        // compute offset for clone
                        const pos = currentContextTarget.position();
                        const newLeft = Math.min(pos.left + 20, container.width() - currentContextTarget
                        .width());
                        const newTop = Math.min(pos.top + 20, container.height() - currentContextTarget
                        .height());
                        clone.css({
                            left: newLeft + 'px',
                            top: newTop + 'px'
                        });
                        // remove existing seats from clone and rebuild properly
                        // gather seat data
                        const seatsData = [];
                        currentContextTarget.find('.section-seat').each(function() {
                            const s = $(this);
                            seatsData.push({
                                x: parseFloat(s.css('left')),
                                y: parseFloat(s.css('top')),
                                label: s.data('label') || s.find('.seat-label').text()
                            });
                        });
                        // remove seats in clone
                        clone.find('.section-seat').remove();
                        container.append(clone);
                        // rebind behavior by calling makeSectionElement with seats
                        const isStage = clone.hasClass('seating-plan-stage');
                        const width = clone.width();
                        const height = clone.height();
                        const rotation = getRotation(currentContextTarget);
                        clone.remove();
                        makeSectionElement(newId, currentContextTarget.data('label') || currentContextTarget
                            .find('h6').text(), newLeft, newTop, width, height, rotation, isStage, seatsData
                            );
                        serializeDesign();
                    } else if (action === 'add-seat') {
                        // Add seat: instruct admin to click location inside section (or double-click to add instantly)
                        alert('Click inside the section to place a new seat, or double-click to cancel.');
                        // one-time click handler
                        const onceHandler = function(e) {
                            const offset = currentContextTarget.offset();
                            const x = e.pageX - offset.left - 9;
                            const y = e.pageY - offset.top - 9;
                            addSeatToSection(currentContextTarget, x, y);
                            $(document).off('click', onceHandler);
                        };
                        // attach a single-use click on the section
                        currentContextTarget.on('click.addseat', function(e) {
                            e.stopPropagation();
                            const offset = currentContextTarget.offset();
                            const x = e.pageX - offset.left - 9;
                            const y = e.pageY - offset.top - 9;
                            addSeatToSection(currentContextTarget, x, y);
                            currentContextTarget.off('click.addseat');
                        });
                    }
                });

                // Save new name
                $('#save-new-name').on('click', function() {
                    const newName = $('#input-new-name').val().trim();
                    if (currentContextTarget && newName) {
                        currentContextTarget.data('label', newName);
                        currentContextTarget.find('h6').text(newName);
                        $('#modal-change-name').modal('hide');
                        serializeDesign();
                    }
                });

                // Save new background color
                $('#save-bgcolor').on('click', function() {
                    const newColor = $('#input-bgcolor').val().trim();
                    if (currentContextTarget && newColor) {
                        currentContextTarget.css('background-color', newColor);
                        $('#modal-change-bgcolor').modal('hide');
                        serializeDesign();
                    }
                });

                // Add initial elements
                $('#add-new-section').click(function() {
                    sectionCounter++;
                    makeSectionElement('section-' + sectionCounter, 'Section ' + sectionCounter, 20 +
                        sectionCounter * 10, 20 + sectionCounter * 10);
                });
                $('#add-stage').click(function() {
                    makeSectionElement('stage-' + uid('stage'), 'Stage', 200, 20, 300, 100, 0, true);
                });

                // Prevent default browser context menu inside container
                container.on('contextmenu', function(e) {
                    e.preventDefault();
                });

                // clicking container deselects seats
                container.on('click', function() {
                    container.find('.section-seat').removeClass('selected');
                });

                // clear designer
                $('#clear-designer').on('click', function() {
                    if (confirm('Clear the entire design?')) {
                        container.empty();
                        serializeDesign();
                    }
                });

                // helper: convert rgb to hex (simple)
                function rgb2hex(rgb) {
                    if (!rgb) return '';
                    const m = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
                    if (!m) return rgb; // maybe already hex
                    return '#' + [1, 2, 3].map(i => parseInt(m[i]).toString(16).padStart(2, '0')).join('');
                }

                // Load existing design if server rendered a value
                (function loadExisting() {
                    try {
                        const raw = $('#venue_seating_plan_design').val() ||
                            "{{ old('seatmap_design') ?? (isset($seatmap) ? addslashes($seatmap->layout_json ?? '') : '') }}";
                        if (raw) {
                            const data = JSON.parse(raw);
                            if (Array.isArray(data)) {
                                data.forEach(sec => {
                                    const id = sec.id || uid('section');
                                    const label = sec.label || 'Section';
                                    const x = sec.x || 10;
                                    const y = sec.y || 10;
                                    const width = sec.width || 150;
                                    const height = sec.height || 100;
                                    const rotation = sec.rotation || 0;
                                    const seats = sec.seats || [];
                                    makeSectionElement(id, label, x, y, width, height, rotation, sec
                                        .isStage || false, seats);
                                });
                                serializeDesign();
                            }
                        }
                    } catch (err) {
                        console.warn('Failed to parse existing seatmap JSON', err);
                    }
                })();

                // ensure serialize before submit
                $('form').on('submit', function() {
                    serializeDesign();
                    return true;
                });

            });
        </script>
    @endpush

</x-admin-app-layout>

<x-admin-app-layout :title="'Seat Map Designer: ' . $plan->name">

    <style>
        :root {
            --grid: 20px;
            --seat-size: 32px;
            /* Increased to fit 3 digits */
            --primary: #009ef7;
            --danger: #f1416c;
        }

        /* WRAPPER */
        #seatmap-designer-wrapper {
            position: relative;
            width: 100%;
            height: 650px;
            border: 1px solid #dcdcdc;
            background: #f8fafc;
            /* Pixel Grid Background */
            background-image:
                linear-gradient(#e4e6ef 1px, transparent 1px),
                linear-gradient(90deg, #e4e6ef 1px, transparent 1px);
            background-size: 20px 20px;
            overflow: hidden;
        }

        /* ITEM (Section / Stage / GA / Table) */
        .sp-item {
            position: absolute;
            border: 1px solid #3b5fff;
            background: rgba(59, 95, 255, 0.06);
            border-radius: 6px;
            box-sizing: border-box;
            user-select: none;
            /* Shadow for depth */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .sp-item.ui-draggable-dragging {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            z-index: 1000 !important;
        }

        /* HEADER (The Drag Handle) */
        .sp-item h6 {
            margin: 0;
            padding: 5px;
            background: #eef1ff;
            border-bottom: 1px solid #d0d6ff;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            user-select: none;
            /* FIX: Must be auto to capture mouse events for dragging */
            pointer-events: auto;
            cursor: move;
            border-radius: 5px 5px 0 0;
        }

        /* SPECIAL TYPES */
        .sp-stage {
            border-color: #ff9800;
            background: rgba(255, 153, 0, 0.10);
        }

        .sp-stage h6 {
            background: #fff4e0;
            border-bottom-color: #ffcc80;
        }

        .sp-ga {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.10);
        }

        .sp-ga h6 {
            background: #e3f9e5;
            border-bottom-color: #a3cfbb;
        }

        .sp-table {
            border-color: #8e44ad;
            background: rgba(142, 68, 173, 0.10);
        }

        .sp-table h6 {
            background: #f3e5f5;
            border-bottom-color: #e1bee7;
        }

        /* ROTATION HANDLE */
        .sp-rotate-handle {
            position: absolute;
            width: 16px;
            height: 16px;
            background: #ffffff;
            border: 1px solid #333;
            border-radius: 50%;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: grab;
            z-index: 10;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* SEAT */
        .sp-seat {
            position: absolute;
            width: var(--seat-size);
            height: var(--seat-size);
            border-radius: 50%;
            background: #28a745;
            color: #fff;
            font-size: 9px;
            /* Slightly smaller font for 3 digits (e.g. B100) */
            font-weight: 600;
            text-align: center;
            line-height: var(--seat-size);
            /* Center vertically */
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
            /* Prevent text wrapping */
            overflow: hidden;
            z-index: 5;
            /* Clip if too long */
        }

        .sp-seat.selected {
            background: #007bff;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #007bff;
        }

        .sp-seat.dead {
            background: #ccc !important;
            opacity: 0.5;
        }

        /* CONTEXT MENU */
        .sp-context-menu {
            position: fixed;
            /* Fixed ensures it appears on top of everything */
            display: none;
            z-index: 99999;
            min-width: 160px;
            background: #fff;
            border: 1px solid #e4e6ef;
            list-style: none;
            padding: 5px 0;
            border-radius: 6px;
            box-shadow: 0 0 20px rgba(0, 0, 0, .1);
        }

        .sp-context-menu li {
            padding: 8px 15px;
            cursor: pointer;
            font-size: 13px;
            color: #3f4254;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sp-context-menu li:hover {
            background: #f4f6fa;
            color: #009ef7;
        }

        /* RESIZE HANDLES (Essential for jQuery UI) */
        .ui-resizable-handle {
            position: absolute;
            font-size: 0.1px;
            display: block;
            z-index: 90;
            opacity: 0;
            /* Hidden by default, visible on hover */
            transition: opacity 0.2s;
        }

        .sp-item:hover .ui-resizable-handle {
            opacity: 1;
        }

        .ui-resizable-se {
            cursor: se-resize;
            width: 12px;
            height: 12px;
            right: 1px;
            bottom: 1px;
            background: #3b5fff;
        }

        .ui-resizable-e {
            cursor: e-resize;
            width: 7px;
            right: -5px;
            top: 0;
            height: 100%;
        }

        .ui-resizable-s {
            cursor: s-resize;
            height: 7px;
            bottom: -5px;
            left: 0;
            width: 100%;
        }

        /* --- ADD TO YOUR CSS --- */

        /* The Blue Drag Box */
        .selection-marquee {
            position: absolute;
            border: 1px dashed #009ef7;
            background-color: rgba(0, 158, 247, 0.15);
            z-index: 99999;
            pointer-events: none;
            /* Clicks pass through */
            display: none;
        }

        /* Highlight selected seats */
        .sp-seat.selected {
            background-color: #009ef7 !important;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #009ef7;
            z-index: 10;
        }
    </style>

    <div class="card card-flush mb-6">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Seat Map Designer</h3>

            <div>
                <button class="btn btn-light-primary btn-sm" id="btn-add-section">
                    <i class="fa fa-th-large me-1"></i> Section
                </button>
                <button class="btn btn-light-secondary btn-sm" id="btn-add-stage">
                    <i class="fa fa-vector-square me-1"></i> Stage
                </button>
                <button class="btn btn-light-warning btn-sm" id="btn-add-ga">
                    <i class="fa fa-users me-1"></i> GA
                </button>
                <button class="btn btn-light-info btn-sm" id="btn-add-table">
                    <i class="fa fa-dot-circle me-1"></i> Table
                </button>

                <button class="btn btn-success btn-sm ms-4" id="btn-save-map">
                    <i class="fa fa-save me-1"></i> Save
                </button>
            </div>
        </div>

        <div class="card-body">
            <div id="seatmap-designer-wrapper"></div>

            <input type="hidden" id="seatmap_raw" name="design_json">
            <input type="hidden" id="seatmap_sections" name="sections">
        </div>
    </div>

    <ul id="menu-section" class="sp-context-menu">
        <li data-action="rename"><i class="fa fa-pen text-muted"></i> Rename</li>
        <li data-action="bg-color"><i class="fa fa-fill-drip text-muted"></i> Background</li>
        <li data-action="generate-rows"><i class="fa fa-th text-muted"></i> Generate Rows</li>
        <li data-action="add-seat"><i class="fa fa-plus-circle text-muted"></i> Add Seat</li>
        <li data-action="duplicate"><i class="fa fa-clone text-muted"></i> Duplicate</li>
        <li data-action="delete" class="text-danger"><i class="fa fa-trash"></i> Delete</li>
    </ul>

    <ul id="menu-stage" class="sp-context-menu">
        <li data-action="rename"><i class="fa fa-pen text-muted"></i> Rename</li>
        <li data-action="bg-color"><i class="fa fa-fill-drip text-muted"></i> Background</li>
        <li data-action="delete" class="text-danger"><i class="fa fa-trash"></i> Delete</li>
    </ul>

    <ul id="menu-table" class="sp-context-menu">
        <li data-action="rename"><i class="fa fa-pen text-muted"></i> Rename</li>
        <li data-action="bg-color"><i class="fa fa-fill-drip text-muted"></i> Background</li>
        <li data-action="table-auto-seats"><i class="fa fa-circle-notch text-muted"></i> Circular Seats</li>
        <li data-action="delete" class="text-danger"><i class="fa fa-trash"></i> Delete</li>
    </ul>

    <ul id="menu-ga" class="sp-context-menu">
        <li data-action="rename"><i class="fa fa-pen text-muted"></i> Rename</li>
        <li data-action="bg-color"><i class="fa fa-fill-drip text-muted"></i> Background</li>
        <li data-action="ga-capacity"><i class="fa fa-users text-muted"></i> Capacity</li>
        <li data-action="delete" class="text-danger"><i class="fa fa-trash"></i> Delete</li>
    </ul>

    <ul id="menu-seat-item" class="sp-context-menu">
        <li data-action="rename-seat"><i class="fa fa-pen text-muted"></i> Rename Seat</li>
        <li data-action="delete-seat" class="text-danger"><i class="fa fa-trash"></i> Delete Seat</li>
    </ul>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            $(function() {

                // ========================================================================
                // GLOBAL VARIABLES
                // ========================================================================
                const WRAPPER = $("#seatmap-designer-wrapper");
                let CURRENT = null; // currently right-clicked item
                let ROW_GEN_TARGET = null; // section selected for row generator
                let LOADED = {!! $designJson !!}; // loaded JSON from database

                // --- ADD TO GLOBALS ---
                const $selectionMarquee = $('<div class="selection-marquee"></div>').appendTo('body');
                // ========================================================================
                // UTILITY: Unique ID
                // ========================================================================
                function uid(prefix = "id") {
                    return prefix + "-" + Math.random().toString(36).substring(2, 10);
                }


                // ========================================================================
                // CREATE BLOCK (Section, Stage, GA, Table)
                // ========================================================================
                function createBlock(type, label, x = 50, y = 50, w = 160, h = 100, rotation = 0, seats = []) {

                    let className = "sp-item";
                    if (type === "stage") className += " sp-stage";
                    if (type === "general_admission") className += " sp-ga";
                    if (type === "table") className += " sp-table";

                    const id = uid(type);

                    const el = $(`
                        <div class="${className}" data-id="${id}" data-type="${type}" data-label="${label}"
                            style="left:${x}px; top:${y}px; width:${w}px; height:${h}px; transform:rotate(${rotation}deg)">

                            <h6>${label}</h6>
                            <div class="sp-rotate-handle"></div>
                        </div>
                    `);

                    WRAPPER.append(el);

                    // Apply Interactions
                    makeDraggable(el);
                    makeResizable(el);
                    makeRotatable(el);
                    bindContextMenu(el);
                    bringToFront(el); // Initial z-index boost

                    if (type === 'seat' || type === 'table') {
                        enableMarqueeSelection(el);
                    }
                    // Load seats if any exist
                    seats.forEach(s => {
                        addSeat(el, s.x, s.y, s.label, s.id, s.dead);
                    });

                    return el;
                }


                // --- ADD THIS NEW FUNCTION ---
                function enableMarqueeSelection($element) {
                    $element.on('mousedown', function(e) {
                        // 1. Ignore if clicking dragging handle (h6) or an existing seat
                        if ($(e.target).is('h6') || $(e.target).hasClass('sp-seat') || $(e.target).hasClass(
                                'sp-rotate-handle')) {
                            return;
                        }

                        // 2. If Ctrl is NOT held, clear previous selection
                        if (!e.ctrlKey && !e.shiftKey) {
                            $element.find('.sp-seat').removeClass('selected');
                        }

                        // 3. Start Drawing Box
                        e.preventDefault(); // Prevent text highlight

                        // Disable dragging of the section temporarily so we can draw
                        if ($element.data('ui-draggable')) $element.draggable('disable');

                        const startX = e.pageX;
                        const startY = e.pageY;

                        $selectionMarquee.css({
                            top: startY,
                            left: startX,
                            width: 0,
                            height: 0,
                            display: 'block'
                        });

                        // 4. Mouse Move (Expand Box & Detect Collision)
                        $(document).on('mousemove.marquee', function(ev) {
                            const currentX = ev.pageX;
                            const currentY = ev.pageY;

                            const width = Math.abs(currentX - startX);
                            const height = Math.abs(currentY - startY);
                            const newX = (currentX < startX) ? currentX : startX;
                            const newY = (currentY < startY) ? currentY : startY;

                            $selectionMarquee.css({
                                width: width,
                                height: height,
                                top: newY,
                                left: newX
                            });

                            // Detect Overlap with Seats
                            $element.find('.sp-seat').each(function() {
                                const $seat = $(this);
                                const seatOff = $seat.offset();

                                // Box Coordinates
                                const boxRight = newX + width;
                                const boxBottom = newY + height;

                                // Seat Coordinates
                                const seatRight = seatOff.left + $seat.width();
                                const seatBottom = seatOff.top + $seat.height();

                                // Collision Check
                                if (newX < seatRight && boxRight > seatOff.left &&
                                    newY < seatBottom && boxBottom > seatOff.top) {
                                    $seat.addClass('selected');
                                }
                            });
                        });

                        // 5. Mouse Up (Finish)
                        $(document).on('mouseup.marquee', function() {
                            $selectionMarquee.hide();
                            $(document).off('mousemove.marquee mouseup.marquee');

                            // Re-enable dragging of the section
                            if ($element.data('ui-draggable')) $element.draggable('enable');
                        });
                    });
                }

                // ========================================================================
                // DRAGGABLE (Fixed Logic)
                // ========================================================================
                function makeDraggable(el) {
                    el.draggable({
                        containment: WRAPPER,
                        handle: "h6", // Drag by header
                        start: function() {
                            bringToFront($(this));
                            $(".sp-context-menu").hide();
                        },
                        stop: saveDesign
                    });

                    // Bring to front on click
                    el.on('mousedown', function() {
                        bringToFront($(this));
                    });
                }

                function bringToFront(el) {
                    // Reset others
                    $(".sp-item").css("z-index", 10);
                    // Boost current
                    el.css("z-index", 50);
                }


                // ========================================================================
                // RESIZABLE
                // ========================================================================
                // function makeResizable(el) {
                //     // Tables shouldn't resize freely usually, but allowing it for flexibility
                //     el.resizable({
                //         containment: WRAPPER,
                //         handles: "n,e,s,w,ne,se,sw,nw",
                //         stop: function() {
                //             enforceSeatBounds(el);
                //             saveDesign();
                //         }
                //     });
                // }

                function makeResizable(el) {
                    // Tables and Sections resize differently
                    const type = el.data('type');

                    el.resizable({
                        containment: WRAPPER,
                        handles: "all", // Enables all resize handles
                        minWidth: 50,
                        minHeight: 50,
                        stop: function(e, ui) {
                            // If table, keep circle shape
                            if (type === 'table') {
                                const s = Math.max(ui.size.width, ui.size.height);
                                el.css({
                                    width: s,
                                    height: s,
                                    borderRadius: '50%'
                                });
                                // Auto-adjust seats to new circle
                                const seats = el.find('.sp-seat');
                                if (seats.length > 0) generateCircularSeats(el, seats.length);
                            }

                            // If Section, enforce bounds
                            if (type === 'seat') {
                                enforceSeatBounds(el);
                            }

                            saveDesign();
                        }
                    });
                }


                // ========================================================================
                // ROTATABLE
                // ========================================================================
                function makeRotatable(el) {
                    const handle = el.find(".sp-rotate-handle");

                    handle.on("mousedown", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const startX = e.pageX;
                        const startAngle = getRotation(el);

                        $(document).on("mousemove.rotate", function(ev) {
                            const dx = ev.pageX - startX;
                            const angle = startAngle + dx * 0.7;
                            el.css("transform", `rotate(${angle}deg)`);
                        });

                        $(document).on("mouseup.rotate", function() {
                            $(document).off("mousemove.rotate mouseup.rotate");
                            saveDesign();
                        });
                    });
                }

                function getRotation(el) {
                    let tf = el.css("transform");
                    if (!tf || tf === "none") return 0;

                    let v = tf.split("(")[1].split(")")[0].split(",");
                    let a = parseFloat(v[0]);
                    let b = parseFloat(v[1]);

                    return Math.round(Math.atan2(b, a) * (180 / Math.PI));
                }


                // ========================================================================
                // SEATS
                // ========================================================================
                // function addSeat(section, x, y, label = null, id = null, dead = false) {

                //     const count = section.find(".sp-seat").length + 1;
                //     const seatLabel = label || "S" + count;
                //     const seatId = id || uid("seat");

                //     const seat = $(`
        //         <div class="sp-seat ${dead ? 'dead' : ''}" data-id="${seatId}" data-label="${seatLabel}" data-dead="${dead ? 1 : 0}"
        //             style="left:${x}px; top:${y}px;">
        //             <span>${seatLabel}</span>
        //         </div>
        //     `);

                //     section.append(seat);

                //     // Draggable
                //     seat.draggable({
                //         containment: section,
                //         stop: saveDesign
                //     });

                //     // Select
                //     seat.on("click", function(e) {
                //         e.stopPropagation();
                //         $(this).toggleClass("selected");
                //     });

                //     // Delete on double click
                //     seat.on("dblclick", function(e) {
                //         e.stopPropagation();
                //         if (confirm("Delete seat?")) {
                //             $(this).remove();
                //             saveDesign();
                //         }
                //     });

                //     return seat;
                // }

                // function addSeat(section, x, y, label = null, id = null, dead = false) {
                //     const count = section.find(".sp-seat").length + 1;
                //     const seatLabel = label || "S" + count;
                //     const seatId = id || uid("seat");

                //     const seat = $(`
        //             <div class="sp-seat ${dead ? 'dead' : ''}"
        //                 data-id="${seatId}"
        //                 data-label="${seatLabel}"
        //                 data-dead="${dead ? 1 : 0}"
        //                 style="left:${x}px; top:${y}px;">
        //                 <span class="seat-lbl">${seatLabel}</span>
        //             </div>
        //         `);

                //     section.append(seat);

                //     // Draggable
                //     seat.draggable({
                //         containment: section,
                //         stop: saveDesign
                //     });

                //     // Select (Left Click)
                //     seat.on("click", function(e) {
                //         e.stopPropagation();
                //         // Handle Multi-select (Ctrl key)
                //         if (e.ctrlKey || e.metaKey) {
                //             $(this).toggleClass("selected");
                //         } else {
                //             // If clicking without Ctrl, select only this one?
                //             // Or keep standard behavior. For now, toggle is safest.
                //             $(this).toggleClass("selected");
                //         }
                //     });

                //     // Right Click (Context Menu) - NEW ADDITION
                //     seat.on("contextmenu", function(e) {
                //         e.preventDefault();
                //         e.stopPropagation(); // Prevent section menu from showing
                //         $(".sp-context-menu").hide(); // Hide other menus

                //         // Store the seat being edited in the menu's data
                //         $("#menu-seat-item").data("target", $(this));

                //         $("#menu-seat-item").css({
                //             left: e.clientX, // Fixed positioning
                //             top: e.clientY
                //         }).show();
                //     });

                //     return seat;
                // }

                // ========================================================================
                // SEATS (Updated for Tooltip + Multi-Drag)
                // ========================================================================
                function addSeat(section, x, y, label = null, id = null, dead = false) {

                    const count = section.find(".sp-seat").length + 1;
                    const seatLabel = label || "S" + count;
                    const seatId = id || uid("seat");

                    // 1. Added 'title' attribute for native browser Tooltip
                    const seat = $(`
                                <div class="sp-seat ${dead ? 'dead' : ''}"
                                    data-id="${seatId}"
                                    data-label="${seatLabel}"
                                    data-dead="${dead ? 1 : 0}"
                                    title="${seatLabel}"
                                    style="left:${x}px; top:${y}px;">
                                    <span>${seatLabel}</span>
                                </div>
                            `);

                    section.append(seat);

                    // 2. Updated Draggable Logic for Multi-Select Moving
                    seat.draggable({
                        containment: section,
                        start: function(e, ui) {
                            // If dragging an unselected seat, select it (and deselect others unless Ctrl pressed)
                            if (!$(this).hasClass('selected')) {
                                if (!e.ctrlKey) section.find('.sp-seat').removeClass('selected');
                                $(this).addClass('selected');
                            }

                            // Record start positions of ALL selected seats relative to the section
                            section.find('.sp-seat.selected').each(function() {
                                const el = $(this);
                                el.data('startPos', el.position());
                            });
                        },
                        drag: function(e, ui) {
                            // Calculate how much the "leader" (dragged seat) moved
                            const dt = ui.position.top - ui.originalPosition.top;
                            const dl = ui.position.left - ui.originalPosition.left;

                            // Move all other selected seats by the same amount
                            section.find('.sp-seat.selected').not(this).each(function() {
                                const el = $(this);
                                const start = el.data('startPos');
                                if (start) {
                                    el.css({
                                        top: start.top + dt,
                                        left: start.left + dl
                                    });
                                }
                            });
                        },
                        stop: saveDesign
                    });

                    // Select Logic
                    seat.on("click", function(e) {
                        e.stopPropagation();
                        // Allow multi-select with Ctrl key, otherwise toggle single
                        if (e.ctrlKey || e.metaKey) {
                            $(this).toggleClass("selected");
                        } else {
                            // If clicking a seat that is already selected (and dragging), don't clear
                            // But for a pure click:
                            const wasSelected = $(this).hasClass('selected');
                            // Optional: Clear others if not holding Ctrl?
                            // section.find('.sp-seat').removeClass('selected');
                            $(this).toggleClass("selected");
                        }
                    });

                    // Delete on double click
                    seat.on("dblclick", function(e) {
                        e.stopPropagation();
                        if (confirm("Delete seat?")) {
                            $(this).remove();
                            saveDesign();
                        }
                    });

                    // Right Click (Context Menu) - NEW ADDITION
                    seat.on("contextmenu", function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent section menu from showing
                        $(".sp-context-menu").hide(); // Hide other menus

                        // Store the seat being edited in the menu's data
                        $("#menu-seat-item").data("target", $(this));

                        $("#menu-seat-item").css({
                            left: e.clientX, // Fixed positioning
                            top: e.clientY
                        }).show();
                    });

                    return seat;
                }

                // Keep seats inside section box
                function enforceSeatBounds(section) {
                    const sw = section.width();
                    const sh = section.height();

                    section.find(".sp-seat").each(function() {
                        let s = $(this);
                        let x = parseFloat(s.css("left"));
                        let y = parseFloat(s.css("top"));
                        const w = s.outerWidth();
                        const h = s.outerHeight();

                        if (x < 0) x = 0;
                        if (y < 0) y = 0;
                        if (x > sw - w) x = sw - w;
                        if (y > sh - h) y = sh - h;

                        s.css({
                            left: x,
                            top: y
                        });
                    });
                }


                // ========================================================================
                // CONTEXT MENU BINDING (Fixed Positioning)
                // ========================================================================
                function bindContextMenu(el) {
                    el.on("contextmenu", function(e) {
                        e.preventDefault();
                        $(".sp-context-menu").hide();

                        CURRENT = el;
                        const type = el.data("type");

                        let menu = $("#menu-section");
                        if (type === "stage") menu = $("#menu-stage");
                        if (type === "table") menu = $("#menu-table");
                        if (type === "general_admission") menu = $("#menu-ga");

                        menu.css({
                            left: e.clientX + 'px', // Use ClientX for Fixed Pos
                            top: e.clientY + 'px'
                        }).show();
                    });
                }

                $(document).on("click", function() {
                    $(".sp-context-menu").hide();
                });


                // ========================================================================
                // LOAD EXISTING JSON
                // ========================================================================
                function loadExisting() {
                    if (!LOADED || !Array.isArray(LOADED)) return;

                    LOADED.forEach(item => {
                        createBlock(
                            item.type,
                            item.name,
                            item.x,
                            item.y,
                            item.width,
                            item.height,
                            item.rotation,
                            item.seats || []
                        );
                    });
                }
                loadExisting();


                // ========================================================================
                // SAVE DESIGN
                // ========================================================================
                function saveDesign() {
                    $("#seatmap_raw").val(JSON.stringify(buildRawJSON()));
                    $("#seatmap_sections").val(JSON.stringify(buildCleanSections()));
                }

                window.saveDesign = saveDesign;


                // ========================================================================
                // TOOLBAR BUTTONS
                // ========================================================================
                $("#btn-add-section").click(() => createBlock("seat", "Section", 80, 80));
                $("#btn-add-stage").click(() => createBlock("stage", "Stage", 150, 60, 260, 80));
                $("#btn-add-ga").click(() => createBlock("general_admission", "GA", 120, 120, 220, 160));
                $("#btn-add-table").click(() => createBlock("table", "Table", 200, 150, 120, 120));

                /* ============================================================================
                   PART 3 — CONTEXT MENU ACTIONS + ROW EDITOR + SAVE TO CONTROLLER
                   ============================================================================ */

                // =========================================================================
                // HELPER: Modal
                // =========================================================================
                function showModal(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        const modal = bootstrap.Modal.getOrCreateInstance(el);
                        modal.show();
                    }
                }

                function hideModal(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        const modal = bootstrap.Modal.getOrCreateInstance(el);
                        modal.hide();
                    }
                }

                // =========================================================================
                // GLOBAL TARGETS
                // =========================================================================
                window.renameTarget = null;
                window.colorTarget = null;
                window.gaTarget = null;

                // =========================================================================
                // CONTEXT MENU ACTION HANDLER
                // =========================================================================
                $(".sp-context-menu li").on("click", function(e) {
                    e.stopPropagation();
                    $(".sp-context-menu").hide();

                    const action = $(this).data("action");
                    if (!CURRENT) return;
                    const type = CURRENT.attr("data-type");

                    switch (action) {
                        case "rename":
                            renameTarget = CURRENT;
                            $("#modal-rename-input").val(renameTarget.attr("data-label"));
                            showModal('modal-rename');
                            break;

                        case "bg-color":
                            colorTarget = CURRENT;
                            $("#modal-bgcolor-input").val(rgbToHex(colorTarget.css("background-color")));
                            showModal('modal-bgcolor');
                            break;

                        case "ga-capacity":
                            if (type !== "general_admission") return;
                            gaTarget = CURRENT;
                            $("#modal-ga-input").val(gaTarget.attr("data-capacity") || 0);
                            showModal('modal-ga');
                            break;

                        case "delete":
                            if (confirm("Delete this item?")) {
                                CURRENT.remove();
                                saveDesign();
                            }
                            break;

                        case "duplicate":
                            duplicateItem(CURRENT);
                            break;

                        case "add-seat":
                            alert("Click inside section to place seat.");
                            CURRENT.one("click.addSeat", function(ev) {
                                const off = CURRENT.offset();
                                const x = ev.pageX - off.left - 10;
                                const y = ev.pageY - off.top - 10;
                                addSeat(CURRENT, x, y);
                                saveDesign();
                            });
                            break;

                        case "generate-rows":
                            ROW_GEN_TARGET = CURRENT;
                            showModal('modal-generate-rows');
                            break;

                        case "table-auto-seats":
                            if (type !== "table") return;
                            const count = parseInt(prompt("Number of seats?", "8"));
                            if (!isNaN(count) && count > 0) generateCircularSeats(CURRENT, count);
                            saveDesign();
                            break;
                    }
                });

                // --- SEAT CONTEXT MENU ACTIONS ---
                $("#menu-seat-item li").on("click", function(e) {
                    e.stopPropagation();
                    $("#menu-seat-item").hide();

                    const action = $(this).data("action");
                    const $seat = $("#menu-seat-item").data("target");

                    if (!$seat) return;

                    if (action === "rename-seat") {
                        const oldLabel = $seat.data("label"); // Use .data() for consistency
                        const newLabel = prompt("Enter Seat Label:", oldLabel);

                        if (newLabel && newLabel.trim() !== "") {
                            // 1. Update Visual Text
                            $seat.find("span").text(newLabel);

                            // 2. Update HTML Attribute (for inspection)
                            $seat.attr("data-label", newLabel);

                            // 3. CRITICAL: Update jQuery Data Cache (This is what saveDesign reads!)
                            $seat.data("label", newLabel);

                            saveDesign();
                        }
                    }

                    if (action === "delete-seat") {
                        if (confirm("Delete this seat?")) {
                            $seat.remove();
                            saveDesign();
                        }
                    }
                });

                // function duplicateItem(el) {
                //     const copy = el.clone();
                //     const newId = "item-" + Math.random().toString(36).substring(2, 9);
                //     copy.attr("data-id", newId);
                //     let pos = el.position();
                //     copy.css({
                //         left: pos.left + 20,
                //         top: pos.top + 20
                //     });

                //     copy.find('.sp-seat').remove();
                //     el.find('.sp-seat').each(function() {
                //         const s = $(this);
                //         addSeat(copy, parseFloat(s.css("left")), parseFloat(s.css("top")), s.attr("data-label"),
                //             "seat-" + Math.random().toString(36).substring(2, 9), s.attr("data-dead") == "1"
                //         );
                //     });

                //     WRAPPER.append(copy);
                //     makeDraggable(copy);
                //     makeResizable(copy);
                //     makeRotatable(copy);
                //     bindContextMenu(copy);
                //     saveDesign();
                // }

                function duplicateItem(el) {
                    // 1. Extract Data from Original
                    const type = el.data('type');
                    const label = el.data('label') + ' (Copy)';

                    // Get dimensions and position
                    const width = el.width();
                    const height = el.height();
                    const pos = el.position();
                    const rotation = getRotation(el); // Keep rotation

                    // 2. Extract Seats Data
                    const seats = [];
                    el.find('.sp-seat').each(function() {
                        const s = $(this);
                        seats.push({
                            x: parseFloat(s.css('left')),
                            y: parseFloat(s.css("top")),
                            label: s.attr('data-label'), // Use attr to get current value
                            id: null, // Generate new ID in addSeat
                            dead: s.hasClass('dead')
                        });
                    });

                    // 3. Create New Clean Block
                    // We offset x/y by 30px so it doesn't perfectly overlap
                    const newEl = createBlock(
                        type,
                        label,
                        pos.left + 30,
                        pos.top + 30,
                        width,
                        height,
                        rotation,
                        seats
                    );

                    // 4. Select the new item
                    // (Optional: Trigger click to visually select it)
                    // newEl.trigger('mousedown');

                    saveDesign();
                }

                $("#modal-rename-save").click(function() {
                    if (!renameTarget) return;
                    const newName = $("#modal-rename-input").val().trim();
                    if (newName) {
                        renameTarget.attr("data-label", newName);
                        renameTarget.find("h6").text(newName);
                        hideModal('modal-rename');
                        saveDesign();
                    }
                });

                $("#modal-bgcolor-save").click(function() {
                    if (!colorTarget) return;
                    const newColor = $("#modal-bgcolor-input").val().trim();
                    colorTarget.css("background-color", newColor);
                    hideModal('modal-bgcolor');
                    saveDesign();
                });

                $("#modal-ga-save").click(function() {
                    if (!gaTarget) return;
                    const cap = parseInt($("#modal-ga-input").val());
                    if (!isNaN(cap)) gaTarget.attr("data-capacity", cap);
                    hideModal('modal-ga');
                    saveDesign();
                });

                function generateCircularSeats(tableEl, count) {
                    tableEl.find(".sp-seat").remove();
                    let radius = Math.min(tableEl.width(), tableEl.height()) / 2 - 18;
                    let cx = tableEl.width() / 2;
                    let cy = tableEl.height() / 2;
                    for (let i = 0; i < count; i++) {
                        let angle = (2 * Math.PI * i) / count;
                        let x = cx + radius * Math.cos(angle) - 10;
                        let y = cy + radius * Math.sin(angle) - 10;
                        addSeat(tableEl, x, y, "T" + (i + 1));
                    }
                }

                $("#btn-apply-row-gen").click(function() {
                    if (!ROW_GEN_TARGET) return;
                    let rows = parseInt($("#gen-rows").val());
                    let cols = parseInt($("#gen-cols").val());
                    let dir = $("#gen-dir").val();
                    let size = parseInt($("#gen-size").val());
                    let gap = parseInt($("#gen-gap").val());

                    const sec = ROW_GEN_TARGET;
                    sec.find(".sp-seat").remove();

                    const W = sec.width();
                    const H = sec.height();
                    const totalW = cols * size + (cols - 1) * gap;
                    const totalH = rows * size + (rows - 1) * gap;
                    const startX = (W - totalW) / 2;
                    const startY = (H - totalH) / 2;

                    let rowLabel = "A";
                    for (let r = 0; r < rows; r++) {
                        let leftToRight = (dir === "ltr");
                        let baseY = startY + r * (size + gap);
                        for (let c = 0; c < cols; c++) {
                            let col = (leftToRight) ? c : (cols - 1 - c);
                            let x = startX + col * (size + gap);
                            let y = baseY;
                            let label = rowLabel + (c + 1);
                            addSeat(sec, x, y, label);
                        }
                        rowLabel = nextRowLabel(rowLabel);
                    }
                    hideModal('modal-generate-rows');
                    saveDesign();
                });

                function nextRowLabel(c) {
                    return String.fromCharCode(c.charCodeAt(0) + 1);
                }

                function rgbToHex(rgb) {
                    if (!rgb) return "#ffffff";
                    let m = rgb.match(/\d+/g);
                    if (!m) return "#ffffff";
                    return "#" + ("0" + parseInt(m[0]).toString(16)).slice(-2) + ("0" + parseInt(m[1]).toString(16))
                        .slice(-2) + ("0" + parseInt(m[2]).toString(16)).slice(-2);
                }

                // =========================================================================
                // SAVE BUTTON
                // =========================================================================
                $("#btn-save-map").click(async function() {
                    const raw = buildRawJSON();
                    const sections = buildCleanSections();
                    $("#seatmap_raw").val(JSON.stringify(raw));
                    $("#seatmap_sections").val(JSON.stringify(sections));

                    try {
                        await axios.post("{{ route('admin.seating-plans.designer.save', $plan->id) }}", {
                            design_json: raw,
                            sections: sections
                        });
                        alert("Saved!");
                    } catch (e) {
                        console.error(e);
                        alert("Save failed: " + (e.response?.data?.message || e.message));
                    }
                });

                // =========================================================================
                // DATA BUILDERS
                // =========================================================================
                window.buildRawJSON = function() {
                    const out = [];
                    $("#seatmap-designer-wrapper .sp-item").each(function() {
                        const el = $(this);
                        const r = {
                            id: el.data("id"),
                            type: el.data("type"),
                            name: el.data("label"),
                            x: parseFloat(el.css("left")),
                            y: parseFloat(el.css("top")),
                            width: el.outerWidth(),
                            height: el.outerHeight(),
                            rotation: getRotation(el),
                            seats: []
                        };
                        el.find(".sp-seat").each(function() {
                            const s = $(this);
                            r.seats.push({
                                id: s.data("id"),
                                label: s.data("label"),
                                dead: s.data("dead"),
                                x: parseFloat(s.css("left")),
                                y: parseFloat(s.css("top"))
                            });
                        });
                        out.push(r);
                    });
                    return out;
                };

                window.buildCleanSections = function() {
                    const out = [];
                    $("#seatmap-designer-wrapper .sp-item").each(function() {
                        const el = $(this);
                        const sec = {
                            name: el.data("label"),
                            type: el.data("type"),
                            capacity: el.attr("data-type") === "general_admission" ? parseInt(el.attr(
                                    "data-capacity") || 0) : el.find(".sp-seat").not("[data-dead=1]")
                                .length,
                            x: parseFloat(el.css("left")),
                            y: parseFloat(el.css("top")),
                            rotation: getRotation(el),
                            seats: []
                        };
                        el.find(".sp-seat").each(function() {
                            const s = $(this);
                            if (s.data("dead") == 1) return;
                            const L = s.data("label");
                            const row = L.match(/[A-Za-z]+/)?.[0] || null;
                            const num = L.match(/\d+/)?.[0] || null;
                            sec.seats.push({
                                label: L,
                                row_label: row,
                                seat_number: num,
                                x: Math.round(parseFloat(s.css("left"))),
                                y: Math.round(parseFloat(s.css("top")))
                            });
                        });
                        out.push(sec);
                    });
                    return out;
                };

                // Global Delete Key Handler
                $(document).on('keydown', function(e) {
                    if (e.key === 'Delete' || e.key === 'Backspace') {
                        const selectedSeats = $('.sp-seat.selected');
                        if (selectedSeats.length > 0) {
                            if (confirm(`Delete ${selectedSeats.length} selected seats?`)) {
                                selectedSeats.remove();
                                saveDesign(); // Update your hidden inputs
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

    @include('admin.pages.seating_plans.partials.designer_modals')
</x-admin-app-layout>

<x-admin-app-layout :title="'Seat Map Designer: ' . $plan->name">

    <style>
        :root {
            --grid-size: 20px;
            --seat-min: 14px;
            --seat-max: 38px;
            --seat-base: 32px;
            --primary: #009ef7;
            --danger: #f1416c;
            --border-soft: #e4e6ef;
        }

        /* ------------------------------------------------------------
            WRAPPER AREA
            ------------------------------------------------------------ */
        #seatmap-designer-wrapper {
            position: relative;
            width: 100%;
            height: 720px;
            border: 1px solid var(--border-soft);
            background: #f8fafc;

            /* Pixel-grid background for layout aligning */
            background-image:
                linear-gradient(#e4e6ef 1px, transparent 1px),
                linear-gradient(90deg, #e4e6ef 1px, transparent 1px);
            background-size: var(--grid-size) var(--grid-size);

            overflow: hidden;
            user-select: none;
        }

        /* ------------------------------------------------------------
            BLOCKS (Sections, Stage, GA, Table)
            ------------------------------------------------------------ */
        .sp-item {
            position: absolute;
            border: 1px solid #3b5fff;
            background: rgba(59, 95, 255, 0.06);
            border-radius: 6px;
            box-sizing: border-box;
            cursor: default;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        /* When dragging */
        .sp-item.ui-draggable-dragging {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            z-index: 999 !important;
        }

        /* HEADER (Drag handle) */
        .sp-item>h6 {
            margin: 0;
            padding: 6px 5px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            background: #eef1ff;
            border-bottom: 1px solid #d0d6ff;
            border-radius: 6px 6px 0 0;
            cursor: move;
            pointer-events: auto;
            user-select: none;
            color: #333;
        }

        /* ------------------------------------------------------------
            SPECIAL BLOCK TYPES
            ------------------------------------------------------------ */
        .sp-stage {
            border-color: #ffa726;
            background: rgba(255, 167, 38, 0.15);
        }

        .sp-stage>h6 {
            background: #fff3e0;
            border-bottom-color: #ffcc80;
        }

        .sp-ga {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.12);
        }

        .sp-ga>h6 {
            background: #e8f5e9;
            border-bottom-color: #a5d6a7;
        }

        .sp-table {
            border-color: #8e44ad;
            background: rgba(142, 68, 173, 0.15);
            border-radius: 50%;
        }

        .sp-table>h6 {
            background: #f3e5f5;
            border-bottom-color: #ce93d8;
        }

        /* ------------------------------------------------------------
                ROTATION HANDLE
                ------------------------------------------------------------ */
        .sp-rotate-handle {
            position: absolute;
            width: 16px;
            height: 16px;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            border: 1px solid #333;
            border-radius: 50%;
            cursor: grab;
            z-index: 20;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* ------------------------------------------------------------
            SMART HYBRID SCALING — Seat Size Controlled via JS
            ------------------------------------------------------------ */
        .sp-seat {
            position: absolute;
            min-width: var(--seat-min);
            min-height: var(--seat-min);
            max-width: var(--seat-max);
            max-height: var(--seat-max);

            width: var(--seat-base);
            height: var(--seat-base);

            background: #28a745;
            border-radius: 50%;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            user-select: none;
            overflow: hidden;
            white-space: nowrap;
            z-index: 20;
            transition: background .1s, transform .1s;
        }

        /* Selected */
        .sp-seat.selected {
            background: var(--primary) !important;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--primary);
            z-index: 30;
        }

        /* Disabled */
        .sp-seat.disabled {
            background: #6c757d !important;
            opacity: 0.75;
            border: 1px solid #444;
        }

        .sp-seat.disabled::after {
            content: "✕";
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            position: absolute;
            top: -3px;
            right: -2px;
        }

        /* ------------------------------------------------------------
            RESIZE HANDLES (jQuery UI)
            ------------------------------------------------------------ */
        .ui-resizable-handle {
            position: absolute;
            display: block;
            opacity: 0;
            z-index: 90;
            transition: opacity .2s;
        }

        .sp-item:hover .ui-resizable-handle {
            opacity: 1;
        }

        /* Bottom-right handle */
        .ui-resizable-se {
            cursor: se-resize;
            width: 12px;
            height: 12px;
            background: #3b5fff;
            right: 0;
            bottom: 0;
        }

        /* East / South handles */
        .ui-resizable-e {
            cursor: e-resize;
            width: 8px;
            right: -4px;
            top: 0;
            height: 100%;
        }

        .ui-resizable-s {
            cursor: s-resize;
            height: 8px;
            bottom: -4px;
            left: 0;
            width: 100%;
        }

        /* ------------------------------------------------------------
        SELECTION MARQUEE (blue drag box)
        ------------------------------------------------------------ */
        .selection-marquee {
            position: fixed;
            border: 1px dashed var(--primary);
            background: rgba(0, 158, 247, 0.15);
            pointer-events: none;
            display: none;
            z-index: 99999;
        }

        /* ------------------------------------------------------------
            CONTEXT MENUS
            ------------------------------------------------------------ */
        .sp-context-menu {
            position: fixed;
            z-index: 999999;
            display: none;
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 6px;
            min-width: 170px;
            padding: 6px 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .sp-context-menu li {
            padding: 8px 14px;
            font-size: 13px;
            cursor: pointer;
            color: #444;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sp-context-menu li:hover {
            background: #f1f5f9;
            color: var(--primary);
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
        <li data-action="rename"><i class="fa fa-pen"></i> Rename</li>
        <li data-action="bg-color"><i class="fa fa-fill-drip"></i> Background</li>
        <li data-action="generate-rows"><i class="fa fa-th"></i> Generate Rows</li>
        <li data-action="add-seat"><i class="fa fa-plus-circle"></i> Add Seat</li>

        <li class="border-top mt-1" style="height:1px;background:#eee;"></li>

        <li data-action="disable-selected-seats"><i class="fa fa-ban text-danger"></i> Disable Selected Seats</li>
        <li data-action="enable-selected-seats"><i class="fa fa-check text-success"></i> Enable Selected Seats</li>
        <li data-action="disable-all-seats"><i class="fa fa-ban text-danger"></i> Disable ALL Seats</li>
        <li data-action="enable-all-seats"><i class="fa fa-check text-success"></i> Enable ALL Seats</li>

        <li class="border-top mt-1" style="height:1px;background:#eee;"></li>

        <li data-action="duplicate"><i class="fa fa-clone"></i> Duplicate</li>
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
        <li data-action="rename-seat"><i class="fa fa-pen"></i> Rename Seat</li>
        <li data-action="toggle-disable-seat"><i class="fa fa-ban"></i> Disable / Enable Seat</li>
        <li data-action="delete-seat" class="text-danger"><i class="fa fa-trash"></i> Delete Seat</li>
    </ul>


    @push('scripts')
        <script>
            // Pass loaded JSON from PHP backend:
            window.LOADED_DESIGN = {!! $designJson !!};

            // Save URL:
            window.SAVE_URL = "{{ route('admin.seating-plans.designer.save', $plan->id) }}";
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


        <script>
            (function() {

                // GLOBAL ROOT ELEMENT
                const WRAPPER = $("#seatmap-designer-wrapper");

                // ------------------------------------------------------------
                // UTILS
                // ------------------------------------------------------------

                function uid(prefix = "id") {
                    return prefix + "-" + Math.random().toString(36).substring(2, 10);
                }

                function getRotation(el) {
                    const tf = el.css("transform");
                    if (!tf || tf === "none") return 0;

                    const v = tf.split("(")[1].split(")")[0].split(",");
                    const a = parseFloat(v[0]);
                    const b = parseFloat(v[1]);

                    return Math.round(Math.atan2(b, a) * (180 / Math.PI));
                }

                function clamp(v, min, max) {
                    return Math.max(min, Math.min(max, v));
                }

                // ------------------------------------------------------------
                // SMART HYBRID AUTO-SCALING ENGINE
                // ------------------------------------------------------------
                const AutoScale = {
                    computeSeatSize(blockEl, seatCount) {

                        if (seatCount === 0) return 32;

                        const w = blockEl.width();
                        const h = blockEl.height();
                        const S = Math.sqrt(seatCount);

                        // Hybrid scaling formula
                        let size = (Math.min(w, h) / S) * 1.15;

                        size = clamp(size, 14, 38);
                        return size;
                    },

                    applySeatSize(blockEl) {
                        const seats = blockEl.find(".sp-seat");
                        const seatCount = seats.length;

                        const newSize = this.computeSeatSize(blockEl, seatCount);

                        seats.css({
                            width: newSize + "px",
                            height: newSize + "px",
                            "line-height": newSize + "px",
                            "font-size": (newSize * 0.32) + "px"
                        });
                    }
                };

                // ------------------------------------------------------------
                // SEAT MANAGER
                // Handles adding, selecting, moving, disabling, deleting seats
                // ------------------------------------------------------------
                const SeatManager = {

                    createSeat(blockEl, x, y, label, id, disabled = false) {

                        const seatId = id || uid("seat");
                        const safeLabel = label || "S";

                        const seat = $(`
                            <div class="sp-seat ${disabled ? 'disabled' : ''}"
                                data-id="${seatId}"
                                data-label="${safeLabel}"
                                data-disabled="${disabled ? 1 : 0}"
                                style="left:${x}px; top:${y}px;">
                                ${safeLabel}
                            </div>
                        `);

                        blockEl.append(seat);
                        this.makeDraggable(seat, blockEl);

                        seat.on("click", (e) => {
                            e.stopPropagation();
                            if (e.ctrlKey || e.metaKey) {
                                seat.toggleClass("selected");
                            } else {
                                // toggle selected without clearing others
                                seat.toggleClass("selected");
                            }
                        });

                        AutoScale.applySeatSize(blockEl);

                        return seat;
                    },

                    makeDraggable(seat, blockEl) {
                        seat.draggable({
                            containment: blockEl,
                            drag: () => {},
                            stop: () => {
                                saveDesign();
                            }
                        });
                    },

                    disableSelected(blockEl) {
                        blockEl.find(".sp-seat.selected").each(function() {
                            $(this).addClass("disabled").attr("data-disabled", 1);
                        });
                        AutoScale.applySeatSize(blockEl);
                    },

                    enableSelected(blockEl) {
                        blockEl.find(".sp-seat.selected").each(function() {
                            $(this).removeClass("disabled").attr("data-disabled", 0);
                        });
                        AutoScale.applySeatSize(blockEl);
                    },

                    disableAll(blockEl) {
                        blockEl.find(".sp-seat").each(function() {
                            $(this).addClass("disabled").attr("data-disabled", 1);
                        });
                        AutoScale.applySeatSize(blockEl);
                    },

                    enableAll(blockEl) {
                        blockEl.find(".sp-seat").each(function() {
                            $(this).removeClass("disabled").attr("data-disabled", 0);
                        });
                        AutoScale.applySeatSize(blockEl);
                    },

                    deleteSelected(blockEl) {
                        blockEl.find(".sp-seat.selected").remove();
                        AutoScale.applySeatSize(blockEl);
                    }
                };

                // ------------------------------------------------------------
                // BLOCK MANAGER
                // Handles creation, duplication, dragging, resizing, rotation
                // ------------------------------------------------------------
                const BlockManager = {

                    create(type, label, x, y, w, h, rotation = 0, seats = []) {

                        let className = "sp-item";
                        if (type === "stage") className += " sp-stage";
                        if (type === "general_admission") className += " sp-ga";
                        if (type === "table") className += " sp-table";

                        const id = uid(type);

                        const el = $(`
                            <div class="${className}"
                                data-id="${id}"
                                data-type="${type}"
                                data-label="${label}"
                                style="left:${x}px; top:${y}px;
                                        width:${w}px; height:${h}px;
                                        transform:rotate(${rotation}deg)">
                                <h6>${label}</h6>
                                <div class="sp-rotate-handle"></div>
                            </div>
                        `);

                        WRAPPER.append(el);

                        this.makeDraggable(el);
                        this.makeResizable(el);
                        this.makeRotatable(el);
                        this.enableMarqueeSelection(el);
                        bringToFront(el);

                        seats.forEach(s => {
                            SeatManager.createSeat(
                                el,
                                s.x,
                                s.y,
                                s.label,
                                s.id,
                                s.disabled == 1
                            );
                        });

                        AutoScale.applySeatSize(el);

                        return el;
                    },

                    makeDraggable(el) {
                        el.draggable({
                            containment: WRAPPER,
                            handle: "h6",
                            start: () => {
                                bringToFront(el);
                                $(".sp-context-menu").hide();
                            },
                            stop: () => saveDesign()
                        });

                        el.on("mousedown", () => bringToFront(el));
                    },

                    makeResizable(el) {

                        el.resizable({
                            containment: WRAPPER,
                            handles: "all",
                            minWidth: 60,
                            minHeight: 60,
                            stop: () => {
                                AutoScale.applySeatSize(el);
                                saveDesign();
                            }
                        });

                        if (el.data("type") === "table") {
                            el.resizable("option", "aspectRatio", 1);
                        }
                    },

                    makeRotatable(el) {
                        const handle = el.find(".sp-rotate-handle");

                        handle.on("mousedown", function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            const startX = e.pageX;
                            const startAngle = getRotation(el);

                            $(document).on("mousemove.rotate", function(ev) {
                                const dx = ev.pageX - startX;
                                const angle = startAngle + dx * 0.8;
                                el.css("transform", `rotate(${angle}deg)`);
                            });

                            $(document).on("mouseup.rotate", function() {
                                $(document).off("mousemove.rotate mouseup.rotate");
                                saveDesign();
                            });
                        });
                    },

                    enableMarqueeSelection(el) {

                        el.on("mousedown", function(e) {
                            if ($(e.target).is("h6") || $(e.target).hasClass("sp-seat") || $(e.target).hasClass(
                                    "sp-rotate-handle"))
                                return;

                            const marquee = $(".selection-marquee");
                            marquee.show();

                            const startX = e.pageX;
                            const startY = e.pageY;

                            if (el.data("ui-draggable"))
                                el.draggable("disable");

                            $(document).on("mousemove.marquee", function(ev) {
                                const mx = Math.min(ev.pageX, startX);
                                const my = Math.min(ev.pageY, startY);

                                const w = Math.abs(ev.pageX - startX);
                                const h = Math.abs(ev.pageY - startY);

                                marquee.css({
                                    left: mx,
                                    top: my,
                                    width: w,
                                    height: h
                                });

                                el.find(".sp-seat").each(function() {
                                    const seat = $(this);
                                    const off = seat.offset();

                                    const sx = off.left;
                                    const sy = off.top;
                                    const sw = seat.width();
                                    const sh = seat.height();

                                    if (mx < sx + sw &&
                                        mx + w > sx &&
                                        my < sy + sh &&
                                        my + h > sy) {
                                        seat.addClass("selected");
                                    }
                                });
                            });

                            $(document).on("mouseup.marquee", function() {
                                marquee.hide();
                                marquee.width(0);
                                marquee.height(0);

                                $(document).off(".marquee");
                                if (el.data("ui-draggable"))
                                    el.draggable("enable");
                            });
                        });
                    },

                    duplicate(el) {

                        const type = el.data("type");
                        const label = el.data("label") + " (Copy)";
                        const pos = el.position();
                        const w = el.width();
                        const h = el.height();
                        const rot = getRotation(el);

                        const seatsData = [];

                        el.find(".sp-seat").each(function() {
                            const s = $(this);

                            seatsData.push({
                                id: uid("seat"),
                                label: s.data("label"),
                                x: parseFloat(s.css("left")),
                                y: parseFloat(s.css("top")),
                                disabled: s.attr("data-disabled") == "1"
                            });
                        });

                        const newEl = this.create(
                            type,
                            label,
                            pos.left + 40,
                            pos.top + 40,
                            w,
                            h,
                            rot,
                            seatsData
                        );

                        bringToFront(newEl);
                        saveDesign();
                    }
                };

                // ------------------------------------------------------------
                // Z-INDEX CONTROL
                // ------------------------------------------------------------
                function bringToFront(el) {
                    $(".sp-item").css("z-index", 10);
                    el.css("z-index", 50);
                }

                // ------------------------------------------------------------
                // GLOBAL DESIGN SERIALIZATION
                // ------------------------------------------------------------
                window.buildRawJSON = function() {

                    const out = [];

                    $("#seatmap-designer-wrapper .sp-item").each(function() {
                        const el = $(this);

                        const block = {
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

                            block.seats.push({
                                id: s.data("id"),
                                label: s.data("label"),
                                disabled: s.attr("data-disabled") == "1" ? 1 : 0,
                                x: parseFloat(s.css("left")),
                                y: parseFloat(s.css("top"))
                            });
                        });

                        out.push(block);
                    });

                    return out;
                };

                window.buildCleanSections = function() {

                    const out = [];

                    $("#seatmap-designer-wrapper .sp-item").each(function() {
                        const el = $(this);

                        const type = el.data("type");

                        const sec = {
                            name: el.data("label"),
                            type,
                            capacity: type === "general_admission" ?
                                parseInt(el.attr("data-capacity") || 0) : el.find(".sp-seat").length,
                            x: parseFloat(el.css("left")),
                            y: parseFloat(el.css("top")),
                            rotation: getRotation(el),
                            seats: []
                        };

                        el.find(".sp-seat").each(function() {
                            const s = $(this);
                            const L = s.data("label");

                            const row = L.match(/[A-Za-z]+/)?.[0] || null;
                            const num = L.match(/\d+/)?.[0] || null;

                            sec.seats.push({
                                label: L,
                                row_label: row,
                                seat_number: num,
                                disabled: s.attr("data-disabled") == "1",
                                x: Math.round(parseFloat(s.css("left"))),
                                y: Math.round(parseFloat(s.css("top")))
                            });
                        });

                        out.push(sec);
                    });

                    return out;
                };

                // ------------------------------------------------------------
                // GLOBAL SAVE HOOK FOR OUTER SCRIPT
                // ------------------------------------------------------------
                window.saveDesign = function() {
                    $("#seatmap_raw").val(JSON.stringify(buildRawJSON()));
                    $("#seatmap_sections").val(JSON.stringify(buildCleanSections()));
                };

                // ------------------------------------------------------------
                // EXPORT CORE FOR USE IN PART 3
                // ------------------------------------------------------------
                window.BlockManager = BlockManager;
                window.SeatManager = SeatManager;
                window.AutoScale = AutoScale;

            })();
        </script>
        {{-- UI LOGIC LAYER --}}
        <script>
            $(function() {

                // ROOT WRAPPER
                const WRAPPER = $("#seatmap-designer-wrapper");

                // Loaded JSON (from backend)
                const LOADED = window.LOADED_DESIGN || [];

                // Context menu target
                let CURRENT = null;
                let ROW_GEN_TARGET = null;
                let renameTarget = null;
                let colorTarget = null;
                let gaTarget = null;

                // ------------------------------------------------------------
                // LOAD EXISTING DESIGN
                // ------------------------------------------------------------
                function loadExisting() {
                    if (!Array.isArray(LOADED)) return;

                    LOADED.forEach(item => {
                        window.BlockManager.create(
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

                // ------------------------------------------------------------
                // TOOLBAR BUTTONS
                // ------------------------------------------------------------
                $("#btn-add-section").click(() =>
                    BlockManager.create("seat", "Section", 80, 80, 160, 120, 0, [])
                );

                $("#btn-add-stage").click(() =>
                    BlockManager.create("stage", "Stage", 120, 60, 260, 80, 0, [])
                );

                $("#btn-add-ga").click(() =>
                    BlockManager.create("general_admission", "GA", 100, 120, 220, 160, 0, [])
                );

                $("#btn-add-table").click(() =>
                    BlockManager.create("table", "Table", 200, 150, 140, 140, 0, [])
                );

                // ------------------------------------------------------------
                // CONTEXT MENU BINDING FOR BLOCKS
                // ------------------------------------------------------------
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

                        menu
                            .css({
                                left: e.clientX,
                                top: e.clientY
                            })
                            .show();
                    });
                }

                // Attach menu to each block on creation
                // Attach context menu to all blocks (dynamic)
                $(document).on("contextmenu", ".sp-item", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    CURRENT = $(this);
                    $(".sp-context-menu").hide();

                    let type = CURRENT.data("type");

                    let menu = $("#menu-section");
                    if (type === "stage") menu = $("#menu-stage");
                    if (type === "table") menu = $("#menu-table");
                    if (type === "general_admission") menu = $("#menu-ga");

                    menu.css({
                        left: e.clientX,
                        top: e.clientY
                    }).show();
                });


                $(document).on("click", function() {
                    $(".sp-context-menu").hide();
                });

                // ------------------------------------------------------------
                // CONTEXT MENU ACTIONS FOR BLOCKS
                // ------------------------------------------------------------
                $("#menu-section li, #menu-stage li, #menu-table li, #menu-ga li").on("click", function(e) {
                    e.stopPropagation();
                    $(".sp-context-menu").hide();

                    const action = $(this).data("action");
                    if (!CURRENT) return;

                    const type = CURRENT.data("type");

                    switch (action) {

                        // ----------------------------------------------------
                        case "rename":
                            renameTarget = CURRENT;
                            $("#modal-rename-input").val(renameTarget.data("label"));
                            showModal("modal-rename");
                            break;

                            // ----------------------------------------------------
                        case "bg-color":
                            colorTarget = CURRENT;
                            $("#modal-bgcolor-input").val(rgbToHex(colorTarget.css("background-color")));
                            showModal("modal-bgcolor");
                            break;

                            // ----------------------------------------------------
                        case "ga-capacity":
                            if (type !== "general_admission") return;
                            gaTarget = CURRENT;
                            $("#modal-ga-input").val(gaTarget.attr("data-capacity") || 0);
                            showModal("modal-ga");
                            break;

                            // ----------------------------------------------------
                        case "delete":
                            if (confirm("Delete this item?")) {
                                CURRENT.remove();
                                saveDesign();
                            }
                            break;

                            // ----------------------------------------------------
                        case "duplicate":
                            BlockManager.duplicate(CURRENT);
                            break;

                            // ----------------------------------------------------
                        case "add-seat":
                            alert("Click inside the section to place a seat.");
                            CURRENT.one("click.addSeat", function(ev) {
                                const off = CURRENT.offset();
                                const x = ev.pageX - off.left - 16;
                                const y = ev.pageY - off.top - 16;
                                SeatManager.createSeat(CURRENT, x, y);
                                saveDesign();
                            });
                            break;

                            // ----------------------------------------------------
                        case "generate-rows":
                            ROW_GEN_TARGET = CURRENT;
                            showModal("modal-generate-rows");
                            break;

                            // ----------------------------------------------------
                        case "table-auto-seats":
                            if (type !== "table") return;
                            const count = parseInt(prompt("Number of seats?", "8"));
                            if (!isNaN(count) && count > 0) {
                                generateCircularSeats(CURRENT, count);
                                saveDesign();
                            }
                            break;

                            // ----------------------------------------------------
                        case "disable-selected-seats":
                            SeatManager.disableSelected(CURRENT);
                            saveDesign();
                            break;

                        case "enable-selected-seats":
                            SeatManager.enableSelected(CURRENT);
                            saveDesign();
                            break;

                        case "disable-all-seats":
                            SeatManager.disableAll(CURRENT);
                            saveDesign();
                            break;

                        case "enable-all-seats":
                            SeatManager.enableAll(CURRENT);
                            saveDesign();
                            break;
                    }
                });

                // ------------------------------------------------------------
                // SEAT CONTEXT MENU
                // ------------------------------------------------------------
                $("#menu-seat-item li").on("click", function(e) {
                    e.stopPropagation();
                    $("#menu-seat-item").hide();

                    const action = $(this).data("action");
                    const seat = $("#menu-seat-item").data("target");
                    if (!seat) return;

                    switch (action) {

                        case "rename-seat":
                            const old = seat.data("label");
                            const nl = prompt("Seat Label:", old);
                            if (nl) {
                                seat.data("label", nl);
                                seat.attr("data-label", nl);
                                seat.text(nl);
                                saveDesign();
                            }
                            break;

                        case "toggle-disable-seat":
                            const isDis = seat.attr("data-disabled") == "1";
                            if (isDis) {
                                seat.removeClass("disabled").attr("data-disabled", 0);
                            } else {
                                seat.addClass("disabled").attr("data-disabled", 1);
                            }
                            saveDesign();
                            break;

                        case "delete-seat":
                            if (confirm("Delete this seat?")) {
                                seat.remove();
                                saveDesign();
                            }
                            break;
                    }
                });

                // Right-click seat binding
                $(document).on("contextmenu", ".sp-seat", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    $(".sp-context-menu").hide();

                    $("#menu-seat-item")
                        .data("target", $(this))
                        .css({
                            left: e.clientX,
                            top: e.clientY
                        })
                        .show();
                });

                // ------------------------------------------------------------
                // ROW GENERATOR
                // ------------------------------------------------------------
                $("#btn-apply-row-gen").click(function() {

                    if (!ROW_GEN_TARGET) return;

                    const rows = parseInt($("#gen-rows").val()) || 5;
                    const cols = parseInt($("#gen-cols").val()) || 10;
                    const dir = $("#gen-dir").val();

                    const size = parseInt($("#gen-size").val()) || 32;
                    const gap = parseInt($("#gen-gap").val()) || 6;

                    const sec = ROW_GEN_TARGET;

                    sec.find(".sp-seat").remove();

                    const W = sec.width();
                    const H = sec.height();

                    const totalW = cols * size + (cols - 1) * gap;
                    const totalH = rows * size + (rows - 1) * gap;

                    const startX = Math.max(0, (W - totalW) / 2);
                    const startY = Math.max(0, (H - totalH) / 2);

                    let rowLabel = "A";

                    for (let r = 0; r < rows; r++) {
                        let leftToRight = dir === "ltr";
                        let baseY = startY + r * (size + gap);

                        for (let c = 0; c < cols; c++) {
                            const col = leftToRight ? c : cols - 1 - c;
                            const x = startX + col * (size + gap);
                            const y = baseY;
                            const label = rowLabel + (c + 1);
                            SeatManager.createSeat(sec, x, y, label);
                        }

                        rowLabel = String.fromCharCode(rowLabel.charCodeAt(0) + 1);
                    }

                    hideModal("modal-generate-rows");
                    saveDesign();
                });

                // ------------------------------------------------------------
                // TABLE CIRCULAR SEAT GENERATOR
                // ------------------------------------------------------------
                function generateCircularSeats(tableEl, count) {

                    tableEl.find(".sp-seat").remove();

                    const r = tableEl.width() / 2 - 20;
                    const cx = tableEl.width() / 2;
                    const cy = tableEl.height() / 2;

                    for (let i = 0; i < count; i++) {
                        const angle = (2 * Math.PI * i) / count;
                        const x = cx + r * Math.cos(angle) - 16;
                        const y = cy + r * Math.sin(angle) - 16;
                        SeatManager.createSeat(tableEl, x, y, "T" + (i + 1));
                    }
                }

                // ------------------------------------------------------------
                // MODALS UTILITIES
                // ------------------------------------------------------------
                function showModal(id) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).show();
                }

                function hideModal(id) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).hide();
                }

                // ------------------------------------------------------------
                // RENAME, COLOR, GA CAPACITY
                // ------------------------------------------------------------
                $("#modal-rename-save").click(function() {
                    if (!renameTarget) return;
                    const newName = $("#modal-rename-input").val().trim();
                    if (newName) {
                        renameTarget.attr("data-label", newName);
                        renameTarget.find("h6").text(newName);
                    }
                    hideModal("modal-rename");
                    saveDesign();
                });

                $("#modal-bgcolor-save").click(function() {
                    if (!colorTarget) return;
                    const nc = $("#modal-bgcolor-input").val();
                    colorTarget.css("background-color", nc);
                    hideModal("modal-bgcolor");
                    saveDesign();
                });

                $("#modal-ga-save").click(function() {
                    if (!gaTarget) return;
                    const cap = parseInt($("#modal-ga-input").val());
                    if (!isNaN(cap)) {
                        gaTarget.attr("data-capacity", cap);
                    }
                    hideModal("modal-ga");
                    saveDesign();
                });

                // ------------------------------------------------------------
                // SAVE BUTTON
                // ------------------------------------------------------------
                $("#btn-save-map").click(async function() {

                    const raw = buildRawJSON();
                    const sections = buildCleanSections();

                    $("#seatmap_raw").val(JSON.stringify(raw));
                    $("#seatmap_sections").val(JSON.stringify(sections));

                    try {
                        await axios.post(SAVE_URL, {
                            design_json: raw,
                            sections
                        });
                        alert("Saved!");
                    } catch (e) {
                        console.error(e);
                        alert("Save failed: " + (e.response?.data?.message || e.message));
                    }
                });

                // ------------------------------------------------------------
                // RGB TO HEX (for color picker)
                // ------------------------------------------------------------
                function rgbToHex(rgb) {
                    if (!rgb) return "#ffffff";
                    const m = rgb.match(/\d+/g);
                    if (!m) return "#ffffff";
                    return (
                        "#" +
                        ("0" + parseInt(m[0]).toString(16)).slice(-2) +
                        ("0" + parseInt(m[1]).toString(16)).slice(-2) +
                        ("0" + parseInt(m[2]).toString(16)).slice(-2)
                    );
                }

                // ------------------------------------------------------------
                // GLOBAL DELETE KEY (Seat Delete)
                // ------------------------------------------------------------
                $(document).on("keydown", function(e) {
                    if (e.key === "Delete" || e.key === "Backspace") {
                        const selected = $(".sp-seat.selected");
                        if (selected.length > 0) {
                            if (confirm(`Delete ${selected.length} selected seats?`)) {
                                selected.remove();
                                saveDesign();
                            }
                        }
                    }
                });

                // ------------------------------------------------------------
                // KEYBOARD MOVEMENT (Arrow Keys)
                // ------------------------------------------------------------
                $(document).on("keydown", function(e) {
                    const seats = $(".sp-seat.selected");
                    if (seats.length === 0) return;

                    let dx = 0,
                        dy = 0;

                    switch (e.key) {
                        case "ArrowUp":
                            dy = -1;
                            break;
                        case "ArrowDown":
                            dy = 1;
                            break;
                        case "ArrowLeft":
                            dx = -1;
                            break;
                        case "ArrowRight":
                            dx = 1;
                            break;
                        default:
                            return;
                    }

                    if (e.shiftKey) {
                        dx *= 10;
                        dy *= 10;
                    }

                    seats.each(function() {
                        const seat = $(this);
                        const parent = seat.closest(".sp-item");

                        let x = parseFloat(seat.css("left")) + dx;
                        let y = parseFloat(seat.css("top")) + dy;

                        const maxX = parent.width() - seat.width();
                        const maxY = parent.height() - seat.height();

                        x = Math.max(0, Math.min(maxX, x));
                        y = Math.max(0, Math.min(maxY, y));

                        seat.css({
                            left: x,
                            top: y
                        });
                    });

                    saveDesign();
                });

            });
        </script>
    @endpush
    @include('admin.pages.seating_plans.partials.designer_modals')

</x-admin-app-layout>

<x-admin-app-layout :title="'Seat Designer: ' . $plan->name">

    @push('styles')
        <style>
            :root {
                --grid-size: 20px;
            }

            /* Layout */
            .designer-card {
                height: 650px;
                display: flex;
                flex-direction: column;
                background: #fff;
                border: 1px solid #e4e6ef;
                border-radius: 8px;
                overflow: hidden;
            }

            /* Toolbar */
            .designer-toolbar {
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 1.2rem;
                border-bottom: 1px solid #eff2f5;
                background: #fff;
            }

            .tool-group {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .btn-tool {
                height: 48px;
                width: 60px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                border: 1px solid #eee;
                border-radius: 6px;
                background: #fff;
                cursor: pointer;
                transition: .2s;
                font-size: 10px;
            }

            .btn-tool:hover {
                border-color: #009ef7;
                color: #009ef7;
            }

            .btn-tool i {
                font-size: 16px;
                margin-bottom: 5px;
                color: #7e8299;
            }

            /* Canvas */
            .canvas-wrapper {
                position: relative;
                flex-grow: 1;
                background-color: #fafafa;
                background-image:
                    linear-gradient(#e4e6ef 1px, transparent 1px),
                    linear-gradient(90deg, #e4e6ef 1px, transparent 1px);
                background-size: var(--grid-size) var(--grid-size);
                cursor: grab;
            }

            .canvas-wrapper:active {
                cursor: grabbing;
            }

            /* Floating Controls */
            .floating-controls {
                position: absolute;
                bottom: 20px;
                right: 20px;
                display: flex;
                gap: 6px;
                z-index: 20;
            }

            /* Context Menu */
            #ctx-menu {
                position: fixed;
                z-index: 999999;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 6px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                padding: 6px 0;
                width: 180px;
            }

            .ctx-item {
                padding: 8px 14px;
                cursor: pointer;
                font-size: 13px;
            }

            .ctx-item:hover {
                background: #f3f4f6;
            }
        </style>
    @endpush

    <div class="designer-card">

        <!-- ================= TOOLBAR ================= -->
        <div class="designer-toolbar">

            <!-- Default Tools -->
            <div id="tools-default" class="tool-group w-100">

                <button class="btn-tool" onclick="openCreateModal('seat')">
                    <i class="fas fa-th"></i> Seats
                </button>

                <button class="btn-tool" onclick="openCreateModal('table')">
                    <i class="fas fa-circle-notch"></i> Table
                </button>

                <button class="btn-tool" onclick="createStage()">
                    <i class="fas fa-vector-square"></i> Stage
                </button>

                <button class="btn-tool" onclick="openCreateModal('general_admission')">
                    <i class="fas fa-users"></i> GA
                </button>

                <button id="btn-snap" class="btn btn-light btn-sm" onclick="toggleSnap()">
                    <i class="fas fa-magnet"></i> Snap ON
                </button>

                <button id="btn-multi" class="btn btn-light btn-sm" onclick="toggleMultiSelect()">
                    <i class="fas fa-object-group"></i> Multi
                </button>

                <button class="btn btn-light btn-sm" onclick="autoFitCanvas()">
                    <i class="fas fa-expand"></i> Fit
                </button>
            </div>


            <!-- Properties Panel -->
            <div id="tools-properties" class="tool-group w-100 d-none">
                <button class="btn btn-light btn-sm" onclick="deselectAll()">
                    <i class="fas fa-arrow-left"></i>
                </button>

                <div class="vr mx-3"></div>

                <div class="d-flex flex-column">
                    <label class="text-muted small fw-bold">Name</label>
                    <input id="p-name" type="text" class="form-control form-control-sm"
                        oninput="updateSelectedProps()">
                </div>

                <div class="d-flex flex-column">
                    <label class="text-muted small fw-bold">Tier</label>
                    <input id="p-tier" type="text" class="form-control form-control-sm"
                        oninput="updateSelectedProps()">
                </div>

                <div id="dim-tools" class="d-flex">
                    <div class="d-flex flex-column ms-3">
                        <label class="text-muted small fw-bold">Rows</label>
                        <input id="p-rows" type="number" class="form-control form-control-sm"
                            onchange="updateSelectedProps()">
                    </div>

                    <div class="d-flex flex-column ms-3">
                        <label class="text-muted small fw-bold">Cols</label>
                        <input id="p-cols" type="number" class="form-control form-control-sm"
                            onchange="updateSelectedProps()">
                    </div>
                </div>

                <div class="d-flex flex-column ms-3">
                    <label class="text-muted small fw-bold">Color</label>
                    <input id="p-color" type="color" class="form-control form-control-color"
                        onchange="updateSectionColor(this.value)">
                </div>

                <div class="ms-4">
                    <button class="btn btn-light-primary btn-sm" onclick="openRowEditor()">
                        <i class="fas fa-list"></i> Edit Rows
                    </button>
                </div>

                <button class="btn btn-light-danger btn-sm ms-4" onclick="deleteSelected()">
                    <i class="fas fa-trash"></i>
                </button>

            </div>


            <!-- Save Button -->
            <div>
                <button id="btn-save" class="btn btn-primary btn-sm" onclick="saveLayout()">
                    <i class="fas fa-save me-1"></i> Save
                </button>
            </div>
        </div>

        <!-- ================= CANVAS ================= -->
        <div class="canvas-wrapper" id="canvas-wrapper">
            <div id="canvas-container" style="height:100%; width:100%;"></div>

            <div class="floating-controls">
                <button class="btn btn-light btn-sm" onclick="zoom(1.1)"><i class="fas fa-plus"></i></button>
                <button class="btn btn-light btn-sm" onclick="zoom(0.9)"><i class="fas fa-minus"></i></button>
                <button class="btn btn-light btn-sm" onclick="resetZoom()"><i class="fas fa-compress"></i></button>
            </div>
        </div>

    </div>



    <!-- ================= CREATE SECTION MODAL (Bootstrap 5) ================= -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create Section</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="m-type">

                    <label class="fw-bold small">Name</label>
                    <input id="m-name" class="form-control mb-3">

                    <label class="fw-bold small">Tier</label>
                    <input id="m-tier" class="form-control mb-3">

                    <div class="row">
                        <div class="col-6">
                            <label class="fw-bold small">Rows</label>
                            <input id="m-rows" type="number" class="form-control" value="5">
                        </div>
                        <div class="col-6">
                            <label class="fw-bold small">Cols</label>
                            <input id="m-cols" type="number" class="form-control" value="10">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary w-100" onclick="createSection()">Create</button>
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://unpkg.com/konva@10/konva.min.js"></script>
        <script>
            /********************************************************************************************
             * PART 2 — CANVAS ENGINE + INTERACTION LAYER
             * Clean, conflict-free, includes:
             * - Stage + Layer init
             * - Panning
             * - Mouse-point dynamic zoom
             * - Proportional scaling (transformer lock)
             * - Snap to grid
             * - Selection system
             * - Multi-select (Ctrl/Toggle)
             ********************************************************************************************/

            // ==============================
            // CONFIG
            // ==============================
            const GRID = 20;
            const SNAP_ENABLED = true;

            const CONFIG = {
                seatRadius: 10,
                seatGap: 8,
                colors: {
                    seat: "#A5A6F6",
                    stage: "#2b2b2b",
                    selected: "#009ef7"
                }
            };

            // ==============================
            // GLOBALS
            // ==============================
            let stage, layer, transformer;
            let selectedGroup = null;
            let selectedItems = [];
            let multiSelectMode = false;

            let isPanning = false;
            let panStart = {
                x: 0,
                y: 0
            };
            let stageStart = {
                x: 0,
                y: 0
            };

            let contextMenuNode = null;

            const SAVED_JSON = {!! $designJson !!};


            // ==============================
            // INIT CANVAS
            // ==============================
            document.addEventListener("DOMContentLoaded", () => {
                initCanvas();
                createModal = new bootstrap.Modal(document.getElementById("createModal"));
            });


            // ==============================
            // INITIALIZE KONVA STAGE
            // ==============================
            function initCanvas() {
                const container = document.getElementById("canvas-container");

                stage = new Konva.Stage({
                    container: "canvas-container",
                    width: container.offsetWidth,
                    height: container.offsetHeight,
                    draggable: false
                });

                layer = new Konva.Layer();
                stage.add(layer);

                transformer = new Konva.Transformer({
                    rotateEnabled: true,
                    keepRatio: true, // RS2-A (Proportional scaling)
                    enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],
                    borderStroke: CONFIG.colors.selected,
                    anchorStroke: CONFIG.colors.selected,
                    anchorFill: '#fff',
                    anchorSize: 9,
                    padding: 5,
                });
                layer.add(transformer);

                guideLayer = new Konva.Layer(); // for snap lines
                stage.add(guideLayer);

                loadInitialJson();

                setupZoom();
                setupPanning();
                setupStageClickDeselect();

                window.addEventListener("resize", resizeCanvas);
            }


            // ==============================
            // LOAD SAVED JSON (if exists)
            // ==============================
            function loadInitialJson() {
                try {
                    if (!SAVED_JSON || !SAVED_JSON.children) return;

                    const temp = Konva.Node.create(SAVED_JSON);
                    const groups = temp.find(".section_group");

                    groups.forEach(g => {
                        g.moveTo(layer);
                        bindGroupEvents(g);
                    });

                    temp.destroy();
                    layer.draw();
                } catch (e) {
                    console.error("JSON load error:", e);
                }
            }


            // ==============================
            // RESIZE CANVAS
            // ==============================
            function resizeCanvas() {
                const wrapper = document.getElementById("canvas-wrapper");
                stage.width(wrapper.offsetWidth);
                stage.height(wrapper.offsetHeight);
                stage.batchDraw();
            }


            // ==============================
            // CREATE SECTION (modal)
            // ==============================
            function openCreateModal(type) {
                document.getElementById("m-type").value = type;
                document.getElementById("m-name").value = "";
                document.getElementById("m-tier").value = "";
                createModal.show();
            }

            function createSection() {
                const type = document.getElementById("m-type").value;
                const name = document.getElementById("m-name").value || "Section";
                const tier = document.getElementById("m-tier").value || "";
                const rows = parseInt(document.getElementById("m-rows").value || 5);
                const cols = parseInt(document.getElementById("m-cols").value || 10);

                const center = getCanvasCenter();

                const group = new Konva.Group({
                    x: center.x,
                    y: center.y,
                    draggable: true,
                    name: "section_group",
                    sectionType: type,
                    sectionName: name,
                    sectionTier: tier,
                    rowCount: rows,
                    colCount: cols,
                    rows: null
                });

                // FIX: Add to layer FIRST, so Konva has a context to draw on
                layer.add(group);

                renderSection(group);
                bindGroupEvents(group);
                selectOne(group);

                createModal.hide();
            }


            // ==============================
            // CREATE STAGE
            // ==============================
            function createStage() {
                const center = getCanvasCenter();

                const group = new Konva.Group({
                    x: center.x,
                    y: center.y,
                    draggable: true,
                    name: "section_group",
                    sectionType: "stage",
                    sectionName: "STAGE",
                    rowCount: 0,
                    colCount: 0
                });

                // FIX: Add to layer FIRST
                layer.add(group);

                renderSection(group);
                bindGroupEvents(group);
                selectOne(group);
            }

            // ==============================
            // PANNING
            // ==============================
            function setupPanning() {
                const container = stage.container();

                container.addEventListener("mousedown", e => {
                    if (e.button !== 0) return;
                    if (e.target !== container) return;

                    isPanning = true;

                    panStart = {
                        x: e.clientX,
                        y: e.clientY
                    };
                    stageStart = {
                        x: stage.x(),
                        y: stage.y()
                    };

                    container.style.cursor = "grabbing";
                });

                container.addEventListener("mousemove", e => {
                    if (!isPanning) return;

                    const dx = e.clientX - panStart.x;
                    const dy = e.clientY - panStart.y;

                    stage.position({
                        x: stageStart.x + dx,
                        y: stageStart.y + dy
                    });

                    stage.batchDraw();
                });

                window.addEventListener("mouseup", () => {
                    if (isPanning) {
                        isPanning = false;
                        stage.container().style.cursor = "default";
                    }
                });
            }


            // ==============================
            // MOUSE-POINT DYNAMIC ZOOM
            // ==============================
            function setupZoom() {
                stage.on("wheel", (e) => {
                    e.evt.preventDefault();

                    const oldScale = stage.scaleX();
                    const pointer = stage.getPointerPosition();
                    const scaleBy = 1.06;

                    const direction = e.evt.deltaY > 0 ? -1 : 1;
                    const newScale = direction > 0 ? oldScale * scaleBy : oldScale / scaleBy;

                    const limitedScale = Math.max(0.25, Math.min(3, newScale));

                    const mousePointTo = {
                        x: (pointer.x - stage.x()) / oldScale,
                        y: (pointer.y - stage.y()) / oldScale
                    };

                    stage.scale({
                        x: limitedScale,
                        y: limitedScale
                    });

                    const newPos = {
                        x: pointer.x - mousePointTo.x * limitedScale,
                        y: pointer.y - mousePointTo.y * limitedScale
                    };

                    stage.position(newPos);
                    stage.batchDraw();
                });
            }


            // ==============================
            // STAGE CLICK — Deselect
            // ==============================
            function setupStageClickDeselect() {
                stage.on("click tap", (e) => {
                    if (e.target === stage) {
                        deselectAll();
                    }
                });
            }


            // ==============================
            // GET CANVAS CENTER COORDINATES
            // ==============================
            function getCanvasCenter() {
                return {
                    x: (stage.width() / 2 - 150) - stage.x(),
                    y: (stage.height() / 2 - 50) - stage.y()
                };
            }


            // ==============================
            // SELECTION SYSTEM
            // ==============================
            function selectOne(group) {
                selectedGroup = group;
                selectedItems = [group];

                transformer.nodes([group]);

                document.getElementById("tools-default").classList.add("d-none");
                document.getElementById("tools-properties").classList.remove("d-none");

                fillPropertyPanel(group);
            }

            function deselectAll() {
                selectedGroup = null;
                selectedItems = [];
                transformer.nodes([]);

                document.getElementById("tools-properties").classList.add("d-none");
                document.getElementById("tools-default").classList.remove("d-none");

                layer.batchDraw();
            }


            // ==============================
            // MULTI-SELECT MODE
            // ==============================
            function toggleMultiSelect() {
                multiSelectMode = !multiSelectMode;

                const btn = document.getElementById("btn-multi");
                btn.classList.toggle("btn-success", multiSelectMode);
            }


            // ==============================
            // BIND EVENTS TO GROUP
            // ==============================
            function bindGroupEvents(group) {

                // Select
                group.on("click tap", (e) => {
                    e.cancelBubble = true;

                    if (multiSelectMode) {
                        toggleMultiAdd(group);
                    } else {
                        selectOne(group);
                    }
                });

                // Drag snap
                group.on("dragend", () => {
                    group.position({
                        x: Math.round(group.x() / GRID) * GRID,
                        y: Math.round(group.y() / GRID) * GRID
                    });
                    layer.batchDraw();
                });

                // Multi-drag alignment
                group.on("dragmove", () => {
                    if (!multiSelectMode || selectedItems.length <= 1) return;

                    const dx = group.x() - group._lastX;
                    const dy = group.y() - group._lastY;

                    group._lastX = group.x();
                    group._lastY = group.y();

                    selectedItems.forEach(g => {
                        if (g === group) return;
                        g.x(g.x() + dx);
                        g.y(g.y() + dy);
                    });

                    layer.batchDraw();
                });

                group.on("dragstart", () => {
                    group._lastX = group.x();
                    group._lastY = group.y();
                });
            }


            // ==============================
            // MULTI-SELECTION ADD/REMOVE
            // ==============================
            function toggleMultiAdd(group) {
                if (selectedItems.includes(group)) {
                    selectedItems = selectedItems.filter(g => g !== group);
                } else {
                    selectedItems.push(group);
                }

                transformer.nodes(selectedItems);
                layer.batchDraw();
            }


            // ==============================
            // SNAP TO GRID DURING DRAG
            // ==============================
            stage.on("dragmove", (e) => {
                const node = e.target;
                if (!node || node.name() !== "section_group") return;
                if (!SNAP_ENABLED) return;

                const gx = Math.round(node.x() / GRID) * GRID;
                const gy = Math.round(node.y() / GRID) * GRID;

                if (Math.abs(gx - node.x()) < 12) node.x(gx);
                if (Math.abs(gy - node.y()) < 12) node.y(gy);

                layer.batchDraw();
            });


            // ==============================
            // ROTATION SNAP
            // ==============================
            const ROT_SNAPS = [0, 15, 30, 45, 60, 90, 120, 135, 150, 180];

            stage.on("transformend", (e) => {
                const node = e.target;
                if (!node || node.name() !== "section_group") return;

                const cur = node.rotation();

                let closest = ROT_SNAPS.reduce((prev, curr) =>
                    Math.abs(curr - cur) < Math.abs(prev - cur) ? curr : prev
                );

                if (Math.abs(closest - cur) < 8) {
                    node.rotation(closest);
                }

                layer.batchDraw();
            });


            // ==============================
            // ZOOM BUTTONS
            // ==============================
            function zoom(factor) {
                stage.scale({
                    x: stage.scaleX() * factor,
                    y: stage.scaleY() * factor
                });
                stage.batchDraw();
            }

            function resetZoom() {
                stage.scale({
                    x: 1,
                    y: 1
                });
                stage.position({
                    x: 0,
                    y: 0
                });
                stage.batchDraw();
            }


            // ==============================
            // SNAP TOGGLE
            // ==============================
            function toggleSnap() {
                const btn = document.getElementById("btn-snap");

                window.SNAP_ENABLED = !window.SNAP_ENABLED;

                btn.classList.toggle("btn-primary", SNAP_ENABLED);
                btn.innerHTML = SNAP_ENABLED ?
                    `<i class="fas fa-magnet"></i> Snap ON` :
                    `<i class="fas fa-magnet"></i> Snap OFF`;
            }
        </script>

        <script>
            /********************************************************************************************
             * PART 3 — CONTEXT MENU + HYBRID ROW EDITOR
             * Supports:
             *  • Right-click menu (Rename / Duplicate / Edit Rows / Delete)
             *  • Hybrid row editor (labels, seats, colors, hidden seats, disabled seats)
             *  • Clean, conflict-free
             ********************************************************************************************/

            let activeRowGroup = null; // The section being edited
            let rowEditorModal = null; // Bootstrap modal instance


            /********************************************************************************************
             * RIGHT-CLICK CONTEXT MENU (Eventic-style)
             ********************************************************************************************/

            stage.container().addEventListener("contextmenu", function(e) {
                e.preventDefault();

                const pos = stage.getPointerPosition();
                const shape = stage.getIntersection(pos);

                if (!shape || shape.getParent().name() !== "section_group") {
                    hideContextMenu();
                    return;
                }

                contextMenuNode = shape.getParent();
                showContextMenu(e.clientX, e.clientY);
            });


            function showContextMenu(x, y) {
                hideContextMenu();

                const menu = document.createElement("div");
                menu.id = "ctx-menu";
                menu.style.cssText = `
                position: fixed;
                top: ${y}px;
                left: ${x}px;
                width: 180px;
                background: #fff;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                z-index: 20000;
                padding: 6px 0;
            `;

                menu.innerHTML = `
                <div class="ctx-item" onclick="ctxRename()">✏ Rename</div>
                <div class="ctx-item" onclick="ctxDuplicate()">📄 Duplicate</div>
                <div class="ctx-item" onclick="openRowEditor()">🪑 Edit Rows / Seats</div>
                <hr class="ctx-hr" />
                <div class="ctx-item text-danger" onclick="ctxDelete()">🗑 Delete</div>
            `;

                document.body.appendChild(menu);

                document.querySelectorAll(".ctx-item").forEach(item => {
                    item.style.padding = "6px 12px";
                    item.style.fontSize = "13px";
                    item.style.cursor = "pointer";

                    item.addEventListener("mouseenter", () => item.style.background = "#f3f4f6");
                    item.addEventListener("mouseleave", () => item.style.background = "transparent");
                });
            }

            function hideContextMenu() {
                const el = document.getElementById("ctx-menu");
                if (el) el.remove();
            }

            document.addEventListener("click", hideContextMenu);


            /********************************************************************************************
             * CONTEXT MENU ACTIONS
             ********************************************************************************************/

            window.ctxRename = function() {
                hideContextMenu();

                const newName = prompt("Enter section name:", contextMenuNode.getAttr("sectionName"));
                if (!newName) return;

                contextMenuNode.setAttr("sectionName", newName);
                renderSection(contextMenuNode);
                layer.draw();
            };

            window.ctxDuplicate = function() {
                hideContextMenu();

                const clone = contextMenuNode.clone({
                    x: contextMenuNode.x() + 40,
                    y: contextMenuNode.y() + 40
                });

                layer.add(clone);
                bindGroupEvents(clone);
                layer.draw();
            };

            window.ctxDelete = function() {
                hideContextMenu();
                if (!confirm("Delete this section?")) return;

                contextMenuNode.destroy();
                layer.draw();
            };


            /********************************************************************************************
             * HYBRID ROW EDITOR — MODAL BUILDER
             ********************************************************************************************/

            window.openRowEditor = function() {
                hideContextMenu();

                activeRowGroup = contextMenuNode;

                buildRowEditorModal();
            };

            function buildRowEditorModal() {

                const old = document.getElementById("rowEditorModal");
                if (old) old.remove();

                rowEditorModal = document.createElement("div");
                rowEditorModal.classList.add("modal", "fade");
                rowEditorModal.id = "rowEditorModal";

                rowEditorModal.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Rows & Seats</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="fw-bold small">Rows</label>
                                        <input id="rowCountInput" type="number" min="1" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="fw-bold small">Seats / Row</label>
                                        <input id="colCountInput" type="number" min="1" class="form-control">
                                    </div>
                                </div>

                                <div id="rowsContainer"></div>

                                <button class="btn btn-light-primary btn-sm mt-3" onclick="addRow()">+ Add Row</button>
                                <button class="btn btn-light-success btn-sm mt-3" onclick="duplicateLastRow()">Duplicate Last</button>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" onclick="saveRows()">Apply</button>
                            </div>

                        </div>
                    </div>
                `;

                document.body.appendChild(rowEditorModal);

                const bs = new bootstrap.Modal(rowEditorModal);
                loadRowEditorData();
                bs.show();
            }


            /********************************************************************************************
             * LOAD ROW SETTINGS INTO MODAL
             ********************************************************************************************/
            function loadRowEditorData() {

                let rows = activeRowGroup.getAttr("rows");

                if (!rows) {
                    const rowCount = activeRowGroup.getAttr("rowCount");
                    const colCount = activeRowGroup.getAttr("colCount");

                    rows = [];

                    for (let r = 0; r < rowCount; r++) {
                        rows.push({
                            label: String.fromCharCode(65 + r),
                            seats: colCount,
                            dir: "ltr",
                            color: "#A5A6F6",
                            hidden: [],
                            disabled: []
                        });
                    }

                    activeRowGroup.setAttr("rows", rows);
                }

                document.getElementById("rowCountInput").value = rows.length;
                document.getElementById("colCountInput").value = rows[0].seats;

                renderRowEditorList(rows);
            }


            /********************************************************************************************
             * RENDER ROW LIST (Label, Seats, Color, etc.)
             ********************************************************************************************/
            function renderRowEditorList(rows) {

                let html = "";

                rows.forEach((r, idx) => {
                    html += `
                    <div class="border rounded p-3 mb-3" data-row="${idx}">
                        <div class="d-flex justify-content-between">
                            <strong>Row ${idx + 1}</strong>
                            <button class="btn btn-sm btn-danger" onclick="deleteRow(${idx})">Delete</button>
                        </div>

                        <div class="row mt-2">
                            <div class="col-3">
                                <label class="fw-bold small">Label</label>
                                <input type="text" class="form-control form-control-sm row-label" value="${r.label}">
                            </div>

                            <div class="col-3">
                                <label class="fw-bold small">Seats</label>
                                <input type="number" class="form-control form-control-sm row-seats" value="${r.seats}">
                            </div>

                            <div class="col-3">
                                <label class="fw-bold small">Direction</label>
                                <select class="form-select form-select-sm row-dir">
                                    <option value="ltr" ${r.dir === "ltr" ? "selected" : ""}>Left → Right</option>
                                    <option value="rtl" ${r.dir === "rtl" ? "selected" : ""}>Right → Left</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="fw-bold small">Color</label>
                                <input type="color" class="form-control form-control-sm row-color" value="${r.color}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="fw-bold small">Hidden Seats</label>
                                <input type="text" class="form-control form-control-sm row-hidden"
                                    placeholder="5,7" value="${r.hidden.join(",")}">
                            </div>

                            <div class="col-6">
                                <label class="fw-bold small">Disabled Seats</label>
                                <input type="text" class="form-control form-control-sm row-disabled"
                                    placeholder="8,10" value="${r.disabled.join(",")}">
                            </div>
                        </div>
                    </div>
                `;
                });

                document.getElementById("rowsContainer").innerHTML = html;
            }


            /********************************************************************************************
             * HYBRID EDITOR — Row controls
             ********************************************************************************************/
            function addRow() {
                let rows = activeRowGroup.getAttr("rows") || [];

                rows.push({
                    label: String.fromCharCode(65 + rows.length),
                    seats: parseInt(document.getElementById("colCountInput").value),
                    dir: "ltr",
                    color: "#A5A6F6",
                    hidden: [],
                    disabled: []
                });

                activeRowGroup.setAttr("rows", rows);
                renderRowEditorList(rows);
            }

            function duplicateLastRow() {
                let rows = activeRowGroup.getAttr("rows");

                if (!rows.length) return;

                let last = JSON.parse(JSON.stringify(rows[rows.length - 1]));
                last.label = String.fromCharCode(last.label.charCodeAt(0) + 1);

                rows.push(last);
                activeRowGroup.setAttr("rows", rows);
                renderRowEditorList(rows);
            }

            function deleteRow(idx) {
                let rows = activeRowGroup.getAttr("rows");

                rows.splice(idx, 1);
                activeRowGroup.setAttr("rows", rows);
                renderRowEditorList(rows);
            }


            /********************************************************************************************
             * SAVE ROW EDITOR BACK INTO KONVA
             ********************************************************************************************/
            function saveRows() {
                let rows = [];
                const rowElements = document.querySelectorAll("#rowsContainer > div");

                rowElements.forEach(el => {
                    const label = el.querySelector(".row-label").value.trim();
                    const seats = parseInt(el.querySelector(".row-seats").value);
                    const dir = el.querySelector(".row-dir").value;
                    const color = el.querySelector(".row-color").value;

                    const hidden = el.querySelector(".row-hidden").value
                        .split(",")
                        .map(n => parseInt(n))
                        .filter(n => !isNaN(n));

                    const disabled = el.querySelector(".row-disabled").value
                        .split(",")
                        .map(n => parseInt(n))
                        .filter(n => !isNaN(n));

                    rows.push({
                        label,
                        seats,
                        dir,
                        color,
                        hidden,
                        disabled
                    });
                });

                activeRowGroup.setAttr("rows", rows);

                // Ensure section dimensions reflect row changes
                activeRowGroup.setAttr("rowCount", rows.length);
                activeRowGroup.setAttr("colCount", Math.max(...rows.map(r => r.seats)));

                renderSection(activeRowGroup);
                layer.batchDraw();

                bootstrap.Modal.getInstance(document.getElementById("rowEditorModal")).hide();
            }
        </script>

        <script>
            /********************************************************************************************
             * PART 4 — RENDER ENGINE
             * Converts group attributes + hybrid “rows[]” model into Konva shapes:
             *   • Seat sections (rows / seats / colors / hidden / disabled)
             *   • GA blocks
             *   • Stage & Table
             ********************************************************************************************/

            function renderSection(group, overrideColor = null) {
                const type = group.getAttr("sectionType");

                group.destroyChildren();

                if (type === "seat") {
                    renderSeatSection(group, overrideColor);
                } else if (type === "general_admission") {
                    renderGA(group);
                } else if (type === "table") {
                    renderTable(group);
                } else if (type === "stage") {
                    renderStage(group);
                }

                // Always add label on top
                addLabel(group);

                // group.draw();
                if (group.getLayer()) {
                    group.getLayer().batchDraw();
                }
            }


            /********************************************************************************************
             * 1) RENDER STAGE
             ********************************************************************************************/
            function renderStage(group) {
                const w = 260,
                    h = 60;

                const bg = new Konva.Rect({
                    x: 0,
                    y: 0,
                    width: w,
                    height: h,
                    fill: "#2b2b2b",
                    cornerRadius: 6,
                    name: "bg_shape",
                });

                group.add(bg);
            }


            /********************************************************************************************
             * 2) RENDER TABLE (round)
             ********************************************************************************************/
            function renderTable(group) {
                const radius = 45;

                const circle = new Konva.Circle({
                    x: 0,
                    y: 0,
                    radius: radius,
                    fill: "#c7f0ff",
                    stroke: "#009ef7",
                    strokeWidth: 2,
                    name: "bg_shape",
                });

                group.add(circle);
            }


            /********************************************************************************************
             * 3) RENDER GENERAL ADMISSION BLOCK
             ********************************************************************************************/
            function renderGA(group) {
                const w = 250;
                const h = 150;

                const bg = new Konva.Rect({
                    x: 0,
                    y: 0,
                    width: w,
                    height: h,
                    fill: "#E6F4EA",
                    stroke: "#67C37A",
                    strokeWidth: 2,
                    cornerRadius: 8,
                    name: "bg_shape",
                });

                group.add(bg);
            }


            /********************************************************************************************
             * 4) RENDER SEAT SECTION (rows, seats, hidden, disabled, colors)
             ********************************************************************************************/
            function renderSeatSection(group, overrideColor = null) {

                const rows = group.getAttr("rows");

                if (!rows) return; // nothing yet

                const rowGap = 38;
                const seatSize = CONFIG.seatSize;
                const seatGap = CONFIG.seatGap;

                let maxCols = 0;

                let y = 0;

                rows.forEach((row, rIndex) => {
                    maxCols = Math.max(maxCols, row.seats);

                    const xStart = 0;

                    for (let s = 0; s < row.seats; s++) {

                        // Hidden seat?
                        if (row.hidden.includes(s + 1)) continue;

                        // X pos standard
                        let xPos = xStart + (s * (seatSize + seatGap));

                        // If RTL
                        if (row.dir === "rtl") {
                            xPos = xStart + ((row.seats - 1 - s) * (seatSize + seatGap));
                        }

                        const seatColor = overrideColor || row.color;

                        const seat = new Konva.Circle({
                            x: xPos,
                            y: y,
                            radius: seatSize,
                            fill: seatColor,
                            stroke: "#4B5563",
                            strokeWidth: 1,
                            name: "seat_shape",
                            seatRow: row.label,
                            seatNum: s + 1,
                            gridPos: `${rIndex}-${s}`,
                            isDead: row.disabled.includes(s + 1)
                        });

                        // Disabled seats look different
                        if (row.disabled.includes(s + 1)) {
                            seat.opacity(0.15);
                            seat.stroke("red");
                        }

                        group.add(seat);
                    }

                    y += rowGap; // move row downward
                });

                // Compute bounding box for background
                const bgWidth = maxCols * (seatSize + seatGap);
                const bgHeight = rows.length * rowGap;

                const bg = new Konva.Rect({
                    x: -20,
                    y: -20,
                    width: bgWidth + 40,
                    height: bgHeight + 40,
                    fill: "#ffffff",
                    stroke: "#cccccc",
                    strokeWidth: 1,
                    name: "bg_shape",
                    cornerRadius: 8
                });

                group.add(bg);
                bg.moveToBottom();
            }


            /********************************************************************************************
             * ADD SECTION LABEL
             ********************************************************************************************/
            function addLabel(group) {
                const name = group.getAttr("sectionName") || "";
                const tier = group.getAttr("sectionTier") || "";

                const label = new Konva.Text({
                    x: 0,
                    y: -30,
                    width: group.width(),
                    text: tier ? `${name} (${tier})` : name,
                    fontSize: 16,
                    fontStyle: "bold",
                    align: "center",
                    name: "label_text",
                    fill: "#111"
                });

                group.add(label);
            }


            /********************************************************************************************
             * AUTO CENTER LABEL (after scaling/moving)
             ********************************************************************************************/
            stage.on("dragmove transform", () => {
                layer.find(".section_group").each(g => {
                    const label = g.findOne(".label_text");
                    const bg = g.findOne(".bg_shape");
                    if (!label || !bg) return;

                    label.width(bg.width());
                    label.x(0);
                    label.y(-30);
                });
            });
        </script>

        <script>
            /********************************************************************************************
             * PART 5 — SAVE TO DATABASE
             * Output:
             *   1) raw JSON     → for visuals (Konva)
             *   2) clean JSON   → for Laravel controller (sections + seats)
             *
             * 100% matches:
             *    SeatingPlanDesignerController@save
             ********************************************************************************************/

            window.saveLayout = async function() {

                const btn = document.getElementById("btn-save");
                const oldHTML = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Saving...`;

                try {

                    /******************************************************
                     * 1) RAW VISUAL JSON (entire Konva scene)
                     ******************************************************/
                    const rawJson = stage.toJSON();


                    /******************************************************
                     * 2) CLEAN INVENTORY (sections + seats)
                     ******************************************************/
                    const sections = [];

                    layer.find(".section_group").each(group => {

                        const type = group.getAttr("sectionType");
                        const name = group.getAttr("sectionName");
                        const tier = group.getAttr("sectionTier") || "";
                        const rows = group.getAttr("rows") || [];
                        const rowCount = group.getAttr("rowCount") || 0;
                        const colCount = group.getAttr("colCount") || 0;

                        // Seats array (for DB)
                        let cleanSeats = [];

                        if (type === "seat") {
                            group.find(".seat_shape").each(s => {

                                if (s.getAttr("isDead")) return; // skip disabled seats

                                cleanSeats.push({
                                    label: s.getAttr("seatRow") + s.getAttr("seatNum"),
                                    row_label: s.getAttr("seatRow"),
                                    seat_number: s.getAttr("seatNum"),
                                    x: Math.round(s.x()),
                                    y: Math.round(s.y())
                                });
                            });
                        }

                        if (type === "table") {
                            // Table acts like a single seat-like position
                            cleanSeats.push({
                                label: "T",
                                row_label: null,
                                seat_number: null,
                                x: Math.round(group.x()),
                                y: Math.round(group.y())
                            });
                        }

                        // GA
                        let gaCapacity = 0;
                        if (type === "general_admission") {
                            gaCapacity = group.getAttr("capacity") || 0;
                        }

                        // Build section object
                        sections.push({
                            name: name,
                            display_name: tier ? `${name} (${tier})` : name,
                            type: type,
                            rows: rowCount,
                            cols: colCount,
                            capacity: type === "general_admission" ? gaCapacity : cleanSeats.length,
                            x: Math.round(group.x()),
                            y: Math.round(group.y()),
                            rotation: Math.round(group.rotation() || 0),
                            seats: cleanSeats
                        });
                    });


                    /******************************************************
                     * 3) SEND TO LARAVEL CONTROLLER
                     ******************************************************/
                    const response = await axios.post(
                        "{{ route('admin.seating-plans.designer.save', $plan->id) }}", {
                            design_json: rawJson,
                            sections: sections
                        }
                    );

                    if (response.data.status === "success") {
                        toastr.success("Seat map saved successfully!");
                    } else {
                        toastr.warning(response.data.message || "Saved with warnings.");
                    }


                } catch (e) {
                    console.error(e);
                    toastr.error("Save failed: " + (e.response?.data?.message || e.message));
                }

                btn.disabled = false;
                btn.innerHTML = oldHTML;
            };
            // ============================================================
            // FIX: MISSING PROPERTY PANEL FUNCTIONS
            // Paste this at the bottom of your script
            // ============================================================

            function fillPropertyPanel(group) {
                if (!group) return;

                // 1. Populate Name & Tier Inputs
                // We use || "" to prevent null values if the attribute isn't set
                document.getElementById("p-name").value = group.getAttr("sectionName") || "";
                document.getElementById("p-tier").value = group.getAttr("sectionTier") || "";

                // 2. Handle Inputs Visibility (Stage has no rows/cols)
                const type = group.getAttr("sectionType");

                // Get references to UI containers
                // (Supports both ID naming conventions used in your previous snippets)
                const dimContainer = document.getElementById("p-group-dimensions") || document.getElementById("dim-tools");
                const seatActions = document.getElementById("p-seat-actions");

                if (type === "stage") {
                    if (dimContainer) dimContainer.classList.add("d-none");
                    if (seatActions) seatActions.classList.add("d-none");
                } else {
                    if (dimContainer) dimContainer.classList.remove("d-none");
                    if (seatActions) seatActions.classList.remove("d-none");

                    // Populate Rows/Cols
                    document.getElementById("p-rows").value = group.getAttr("rowCount") || 0;
                    document.getElementById("p-cols").value = group.getAttr("colCount") || 0;
                }

                // 3. Populate Color Picker
                // We look for a seat or background shape to grab the current color
                const shape = group.findOne(".seat_shape") || group.findOne(".bg_shape");
                if (shape) {
                    document.getElementById("p-color").value = shape.fill();
                }
            }

            // Called when typing in Name, Tier, Rows, or Cols inputs
            window.updateSelectedProps = function() {
                if (!selectedGroup) return;

                // Save values to the Konva Group
                selectedGroup.setAttr("sectionName", document.getElementById("p-name").value);
                selectedGroup.setAttr("sectionTier", document.getElementById("p-tier").value);

                if (selectedGroup.getAttr("sectionType") !== "stage") {
                    const r = parseInt(document.getElementById("p-rows").value) || 1;
                    const c = parseInt(document.getElementById("p-cols").value) || 1;
                    selectedGroup.setAttr("rowCount", r);
                    selectedGroup.setAttr("colCount", c);
                }

                // Re-draw the shape with new settings
                renderSection(selectedGroup);
                layer.batchDraw();
            };

            // Called when changing the Color Picker
            window.updateSectionColor = function(color) {
                if (!selectedGroup) return;

                // Re-render with specific color override
                renderSection(selectedGroup, color);
                layer.batchDraw();
            };

            // Called by Delete button
            window.deleteSelected = function() {
                if (selectedGroup) {
                    selectedGroup.destroy();
                    deselectAll();
                    layer.batchDraw();
                }
            };
            // ============================================================
            // FIX: ROW EDITOR LOGIC (Advanced Row/Seat Manager)
            // Paste this at the bottom of your script
            // ============================================================

            let activeRowGroup = null;
            let rowEditorModal = null;

            // 1. Open the Modal
            window.openRowEditor = function() {
                // Use the global selectedGroup from your existing code
                if(!selectedGroup) return;

                activeRowGroup = selectedGroup;
                buildRowEditorModal();
            };

            // 2. Build the Modal HTML dynamically
            function buildRowEditorModal() {
                // Remove old modal if it exists
                const old = document.getElementById("rowEditorModal");
                if (old) old.remove();

                // Create Modal DOM
                rowEditorModal = document.createElement("div");
                rowEditorModal.classList.add("modal", "fade");
                rowEditorModal.id = "rowEditorModal";
                rowEditorModal.setAttribute("tabindex", "-1");

                rowEditorModal.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Rows & Seats</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="fw-bold small">Total Rows</label>
                                        <input id="re-rowCount" type="number" min="1" class="form-control" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="fw-bold small">Default Seats/Row</label>
                                        <input id="re-colCount" type="number" min="1" class="form-control">
                                    </div>
                                </div>
                                <div id="re-rowsContainer" style="max-height: 400px; overflow-y: auto;"></div>
                                <button class="btn btn-light-primary btn-sm mt-3" onclick="reAddRow()">+ Add Row</button>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" onclick="reSaveRows()">Apply Changes</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(rowEditorModal);

                // Initialize Bootstrap Modal
                const bsModal = new bootstrap.Modal(rowEditorModal);
                loadRowEditorData();
                bsModal.show();
            }

            // 3. Load Data from Konva into Modal
            function loadRowEditorData() {
                // Get existing advanced row data, or generate defaults based on dimensions
                let rows = activeRowGroup.getAttr("rows");
                const rowCount = activeRowGroup.getAttr("rowCount") || 1;
                const colCount = activeRowGroup.getAttr("colCount") || 1;

                // If no specific row data exists yet, generate it
                if (!rows || rows.length === 0) {
                    rows = [];
                    for (let r = 0; r < rowCount; r++) {
                        rows.push({
                            label: String.fromCharCode(65 + r), // A, B, C...
                            seats: colCount,
                            color: document.getElementById('p-color').value || '#b5b5c3',
                            hidden: [],
                            disabled: []
                        });
                    }
                }

                document.getElementById("re-rowCount").value = rows.length;
                document.getElementById("re-colCount").value = colCount;

                renderRowEditorList(rows);
            }

            // 4. Render the list of rows inside the modal
            function renderRowEditorList(rows) {
                let html = "";
                rows.forEach((r, idx) => {
                    html += `
                    <div class="border rounded p-2 mb-2 bg-light row-item" data-idx="${idx}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Row ${idx + 1}</strong>
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="reDeleteRow(${idx})"><i class="fas fa-trash"></i></button>
                        </div>
                        <div class="row g-2">
                            <div class="col-3">
                                <label class="small text-muted">Label</label>
                                <input type="text" class="form-control form-control-sm re-label" value="${r.label}">
                            </div>
                            <div class="col-3">
                                <label class="small text-muted">Seats</label>
                                <input type="number" class="form-control form-control-sm re-seats" value="${r.seats}">
                            </div>
                            <div class="col-3">
                                <label class="small text-muted">Color</label>
                                <input type="color" class="form-control form-control-color w-100 re-color" value="${r.color || '#b5b5c3'}">
                            </div>
                            <div class="col-3">
                                <label class="small text-muted">Hidden (e.g. 1,5)</label>
                                <input type="text" class="form-control form-control-sm re-hidden" value="${r.hidden ? r.hidden.join(',') : ''}">
                            </div>
                        </div>
                    </div>`;
                });
                document.getElementById("re-rowsContainer").innerHTML = html;
            }

            // 5. Add a new row in the editor
            window.reAddRow = function() {
                const container = document.getElementById("re-rowsContainer");
                const count = container.children.length;
                const defaultCols = document.getElementById("re-colCount").value || 10;

                // Append logic handled by refreshing data from DOM + new item
                // For simplicity in this fix, we will just grab current DOM state + 1
                const currentData = scrapeModalData();
                currentData.push({
                    label: String.fromCharCode(65 + count),
                    seats: parseInt(defaultCols),
                    color: '#b5b5c3',
                    hidden: [],
                    disabled: []
                });
                renderRowEditorList(currentData);
                document.getElementById("re-rowCount").value = currentData.length;
            };

            // 6. Delete a row in the editor
            window.reDeleteRow = function(idx) {
                const currentData = scrapeModalData();
                currentData.splice(idx, 1);
                renderRowEditorList(currentData);
                document.getElementById("re-rowCount").value = currentData.length;
            };

            // Helper: Scrape data from modal inputs
            function scrapeModalData() {
                const els = document.querySelectorAll(".row-item");
                const data = [];
                els.forEach(el => {
                    const label = el.querySelector(".re-label").value;
                    const seats = parseInt(el.querySelector(".re-seats").value);
                    const color = el.querySelector(".re-color").value;
                    const hiddenStr = el.querySelector(".re-hidden").value;

                    const hidden = hiddenStr.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n));

                    data.push({ label, seats, color, hidden, disabled: [] }); // Preserving structure
                });
                return data;
            }

            // 7. Save changes back to Konva
            window.reSaveRows = function() {
                const rows = scrapeModalData();

                // Update Group Attributes
                activeRowGroup.setAttr("rows", rows);
                activeRowGroup.setAttr("rowCount", rows.length);

                // Update max cols based on the widest row
                const maxCols = Math.max(...rows.map(r => r.seats));
                activeRowGroup.setAttr("colCount", maxCols);

                // Update Properties Panel Inputs
                document.getElementById("p-rows").value = rows.length;
                document.getElementById("p-cols").value = maxCols;

                // Redraw
                renderSection(activeRowGroup);

                // Hide Modal
                const modalEl = document.getElementById("rowEditorModal");
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide();
            };
        </script>
    @endpush
</x-admin-app-layout>

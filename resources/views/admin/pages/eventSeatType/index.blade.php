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
                }

                .seating-plan-section,
                .seating-plan-stage {
                    position: absolute;
                    border: 1px solid #333;
                    background: rgba(200, 200, 255, 0.3);
                    overflow: hidden;
                    padding: 4px;
                    cursor: move;
                }

                .seating-plan-section h6,
                .seating-plan-stage h6 {
                    margin: 0;
                    font-size: 12px;
                    text-align: center;
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
                }

                .context-menu-list li {
                    padding: 8px 12px;
                    cursor: pointer;
                }

                .context-menu-list li:hover {
                    background: #eee;
                }
            </style>

            <form method="POST" action="{{ route('admin.seatmap.save') }}">
                @csrf

                <div id="seating-plan-designer-container"></div>

                <input type="hidden" id="venue_seating_plan_design" name="seatmap_design">

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Save Seat Map</button>
                </div>
            </form>

            {{-- Context Menus --}}
            <ul id="context-menu-section" class="context-menu-list">
                <li data-action="manage-seats">Manage section seats</li>
                <li data-action="change-name">Change section name</li>
                <li data-action="change-bgcolor">Change background color</li>
                <li data-action="duplicate-section">Duplicate section</li>
                <li data-action="delete-section">Delete section</li>
            </ul>

            <ul id="context-menu-stage" class="context-menu-list">
                <li data-action="change-stage-name">Change stage name</li>
                <li data-action="change-stage-bgcolor">Change stage background color</li>
            </ul>

            {{-- Modals --}}
            {{-- Manage Seats Modal --}}
            <div class="modal fade" id="modal-manage-seats" tabindex="-1" aria-labelledby="modal-manage-seats-label"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-light">
                            <h5 class="modal-title" id="modal-manage-seats-label">Manage Section Seats</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <button type="button" class="btn btn-sm btn-primary add-new-row">Add a new row</button>
                            <div class="section-rows-container mt-2">
                                <!-- dynamically inserted row forms -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="save-section-seats">Save Seats</button>
                        </div>
                    </div>
                </div>
            </div>

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

                function makeSectionElement(id, label = 'Section', x = 10, y = 10, w = 150, h = 100, rotation = 0,
                    isStage = false) {
                    const cls = isStage ? 'seating-plan-stage' : 'seating-plan-section';
                    const el = $(`
              <div class="${cls}" data-id="${id}" data-label="${label}" style="left:${x}px; top:${y}px; width:${w}px; height:${h}px; transform:rotate(${rotation}deg);">
                <h6>${label}</h6>
                <div class="ui-rotatable-handle"></div>
              </div>
            `);
                    el.appendTo(container);

                    el.draggable({
                        containment: container,
                        stop: serializeDesign
                    }).resizable({
                        containment: container,
                        handles: "n, e, s, w, ne, se, sw, nw",
                        stop: serializeDesign
                    });

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

                function serializeDesign() {
                    const design = [];
                    container.find('.seating-plan-section, .seating-plan-stage').each(function() {
                        const el = $(this);
                        const id = el.data('id');
                        const label = el.data('label') || el.find('h6').text();
                        const pos = el.position();
                        const w = el.width(),
                            h = el.height();
                        const rotation = getRotation(el);
                        design.push({
                            id,
                            label,
                            x: pos.left,
                            y: pos.top,
                            width: w,
                            height: h,
                            rotation
                        });
                    });
                    $("#venue_seating_plan_design").val(JSON.stringify(design));
                }

                function hideAllContextMenus() {
                    $(".context-menu-list").hide();
                }

                function showContextMenu(el, isStage, x, y) {
                    hideAllContextMenus();
                    currentContextTarget = el;
                    const menu = isStage ? $("#context-menu-stage") : $("#context-menu-section");
                    // position
                    menu.css({
                        top: y + "px",
                        left: x + "px"
                    }).show();
                }

                // close context menu when clicking elsewhere
                $(document).on('click', function() {
                    hideAllContextMenus();
                });

                // handle context menu clicks
                $(".context-menu-list li").on('click', function(e) {
                    e.stopPropagation();
                    hideAllContextMenus();
                    const action = $(this).data('action');
                    if (!currentContextTarget) return;

                    if (action === 'change-name' || action === 'change-stage-name') {
                        const currentName = currentContextTarget.data('label') || currentContextTarget.find(
                            'h6').text();
                        $("#input-new-name").val(currentName);
                        $("#modal-change-name").modal('show');
                    } else if (action === 'manage-seats') {
                        $("#modal-manage-seats").modal('show');
                    } else if (action === 'change-bgcolor' || action === 'change-stage-bgcolor') {
                        const bg = currentContextTarget.css('background-color');
                        $("#input-bgcolor").val(bg);
                        $("#modal-change-bgcolor").modal('show');
                    } else if (action === 'delete-section') {
                        currentContextTarget.remove();
                        serializeDesign();
                    } else if (action === 'duplicate-section') {
                        const clone = currentContextTarget.clone();
                        const newId = 'section-' + (++sectionCounter);
                        clone.attr('data-id', newId);
                        clone.data('label', currentContextTarget.data('label'));
                        clone.find('h6').text(currentContextTarget.data('label'));
                        container.append(clone);
                        // rebind same behaviors
                        makeSectionElement(newId, currentContextTarget.data('label'),
                            clone.position().left + 20, clone.position().top + 20,
                            clone.width(), clone.height(), getRotation(clone),
                            clone.hasClass('seating-plan-stage'));
                        serializeDesign();
                    }
                });

                // Save new name
                $("#save-new-name").on('click', function() {
                    const newName = $("#input-new-name").val().trim();
                    if (currentContextTarget && newName) {
                        currentContextTarget.data('label', newName);
                        currentContextTarget.find('h6').text(newName);
                        $("#modal-change-name").modal('hide');
                        serializeDesign();
                    }
                });

                // Save new background color
                $("#save-bgcolor").on('click', function() {
                    const newColor = $("#input-bgcolor").val().trim();
                    if (currentContextTarget && newColor) {
                        currentContextTarget.css('background-color', newColor);
                        $("#modal-change-bgcolor").modal('hide');
                    }
                });

                // Add initial elements
                $("#add-new-section").click(function() {
                    sectionCounter++;
                    makeSectionElement('section-' + sectionCounter, 'Section ' + sectionCounter);
                });
                $("#add-stage").click(function() {
                    makeSectionElement('stage', 'Stage', 200, 20, 250, 80, 0, true);
                });

                // Prevent default browser context menu inside container
                container.on('contextmenu', function(e) {
                    e.preventDefault();
                });

            });
        </script>
    @endpush

</x-admin-app-layout>

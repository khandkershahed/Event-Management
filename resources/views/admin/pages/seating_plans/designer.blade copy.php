<x-admin-app-layout :title="'Seat Map Designer'">

    <!-- ===================== STYLES ===================== -->
    <style>
        #designerCanvas {
            width: 100%;
            height: 650px;
            border: 2px dashed #d1d1d1;
            background: #fafafa;
            position: relative;
        }

        .tool-btn { margin-right: 10px; }
        .selected { stroke: #ff0000 !important; stroke-width: 2 !important; }
    </style>

    <!-- ===================== CARD WRAPPER ===================== -->
    <div class="card card-flash">

        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $plan->name }} — Seat Map Designer</h3>

            <div>
                <button id="saveDesignBtn" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Layout
                </button>
                <a href="{{ route('admin.seating-plans.index') }}" class="btn btn-light-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">

            <!-- ===================== TOOLS ===================== -->
            <div class="mb-4">
                <button class="btn btn-light border tool-btn" id="openCreateSectionModal">
                    <i class="fas fa-layer-group me-1"></i> Add Section
                </button>

                <button class="btn btn-light border tool-btn" id="addSeatBtn">
                    <i class="fas fa-chair me-1"></i> Add Seat
                </button>

                <button class="btn btn-light border tool-btn" id="deleteBtn">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>

            <!-- ===================== CANVAS ===================== -->
            <div id="designerCanvas"></div>

        </div>

    </div>

    <!-- ===================== CREATE SECTION MODAL ===================== -->
    @include('admin.pages.seating_plans.partials.section-create-modal')

    <!-- ===================== EDIT SECTION MODAL ===================== -->
    @include('admin.pages.seating_plans.partials.section-edit-modal')


    <!-- ===================== SCRIPTS ===================== -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/konva@9/konva.min.js"></script>

        <script>
            // =====================================================================================
            // GLOBALS
            // =====================================================================================
            let designJson = {!! $designJson !!};
            window.__AUTO_EXTRACTED_SECTIONS = [];

            let stage = new Konva.Stage({
                container: 'designerCanvas',
                width: document.getElementById('designerCanvas').offsetWidth,
                height: 650,
            });

            let layer = new Konva.Layer();
            stage.add(layer);


            // =====================================================================================
            // LOAD EXISTING SHAPES
            // =====================================================================================
            if (designJson && Array.isArray(designJson) && designJson.length > 0) {
                Konva.Node.create({ children: designJson }, stage);
            }


            // =====================================================================================
            // SECTION CREATION
            // =====================================================================================
            document.getElementById("openCreateSectionModal").addEventListener("click", function () {
                new bootstrap.Modal(document.getElementById("createSectionModal")).show();
            });

            document.getElementById("createSectionBtn").addEventListener("click", function () {
                const name = document.getElementById("new_section_name").value.trim();
                const type = document.getElementById("new_section_type").value;
                const capacity = parseInt(document.getElementById("new_section_capacity").value || 0);

                if (!name) return alert("Section name required");

                const group = new Konva.Group({
                    x: 80,
                    y: 80,
                    draggable: true,
                    name: 'section',
                });

                const rect = new Konva.Rect({
                    width: 180,
                    height: 100,
                    fill: '#d0ebff',
                    stroke: '#1c7ed6',
                    strokeWidth: 2,
                    name: "rect",
                });

                const text = new Konva.Text({
                    text: name,
                    fontSize: 16,
                    x: 10,
                    y: 10,
                    fill: '#1c7ed6',
                    name: "label",
                });

                // Attach meta data
                group.attrs.meta = { name, type, capacity, rotation: 0 };

                group.add(rect);
                group.add(text);
                layer.add(group);
                layer.draw();

                bootstrap.Modal.getInstance(document.getElementById("createSectionModal")).hide();
            });


            // =====================================================================================
            // SEAT CREATION
            // =====================================================================================
            document.getElementById('addSeatBtn').onclick = function () {
                const circle = new Konva.Circle({
                    x: 100,
                    y: 100,
                    radius: 12,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 1,
                    draggable: true,
                    name: 'seat',
                });

                circle.attrs.meta = {
                    label: "Seat",
                };

                layer.add(circle);
                layer.draw();
            };


            // =====================================================================================
            // DELETE NODE
            // =====================================================================================
            document.getElementById('deleteBtn').onclick = function () {
                const selected = stage.find('.selected')[0];
                if (!selected) return;
                selected.destroy();
                layer.draw();
            };


            // =====================================================================================
            // SELECT SHAPE
            // =====================================================================================
            stage.on("click", function (e) {
                stage.find(".selected").forEach(n => n.removeName("selected"));

                if (e.target === stage) return;

                e.target.addName("selected");
                layer.draw();

                if (e.target.getParent()?.attrs?.name === "section") {
                    openEditSectionModal(e.target.getParent());
                }
            });


            // =====================================================================================
            // OPEN EDIT MODAL
            // =====================================================================================
            function openEditSectionModal(sectionNode) {
                const meta = sectionNode.attrs.meta;

                document.getElementById("edit_section_id").value = sectionNode._id;
                document.getElementById("edit_section_name").value = meta.name;
                document.getElementById("edit_section_type").value = meta.type;
                document.getElementById("edit_section_capacity").value = meta.capacity;
                document.getElementById("edit_section_rotation").value = meta.rotation;

                new bootstrap.Modal(document.getElementById("editSectionModal")).show();
            }

            document.getElementById("saveSectionChangesBtn").addEventListener("click", function () {
                const id = document.getElementById("edit_section_id").value;
                const name = document.getElementById("edit_section_name").value.trim();
                const type = document.getElementById("edit_section_type").value;
                const capacity = parseInt(document.getElementById("edit_section_capacity").value || 0);
                const rotation = parseInt(document.getElementById("edit_section_rotation").value || 0);

                const node = stage.findOne(`#${id}`);
                if (!node) return;

                node.attrs.meta = { name, type, capacity, rotation };
                node.rotation(rotation);

                const label = node.findOne(".label");
                if (label) label.text(name);

                layer.draw();
                bootstrap.Modal.getInstance(document.getElementById("editSectionModal")).hide();
            });


            // =====================================================================================
            // AUTO-EXTRACT JSON FOR SAVE
            // =====================================================================================
            function extractSectionsAndSeats() {
                const sections = [];

                stage.find("Group[name='section']").forEach(sectionNode => {
                    const meta = sectionNode.attrs.meta;

                    const rect = sectionNode.findOne("Rect");
                    const seats = [];

                    stage.find("Circle[name='seat']").forEach(seat => {
                        if (
                            seat.x() > sectionNode.x() &&
                            seat.x() < sectionNode.x() + rect.width() &&
                            seat.y() > sectionNode.y() &&
                            seat.y() < sectionNode.y() + rect.height()
                        ) {
                            seats.push({
                                label: seat.attrs.meta.label,
                                x: seat.x(),
                                y: seat.y(),
                            });
                        }
                    });

                    sections.push({
                        name: meta.name,
                        type: meta.type,
                        capacity: meta.capacity,
                        rotation: meta.rotation,
                        x: sectionNode.x(),
                        y: sectionNode.y(),
                        seats: seats,
                    });
                });

                return sections;
            }


            // =====================================================================================
            // SAVE BUTTON
            // =====================================================================================
            document.getElementById("saveDesignBtn").onclick = async function () {

                window.__AUTO_EXTRACTED_SECTIONS = extractSectionsAndSeats();

                const json = layer.toJSON();

                const response = await fetch("{{ route('admin.seating-plans.designer.save', $plan->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({
                        design_json: json,
                        sections: window.__AUTO_EXTRACTED_SECTIONS,
                    }),
                });

                const text = await response.text();
                console.log("SAVE RESPONSE RAW:", text);

                try {
                    const data = JSON.parse(text);
                    alert(data.message);
                } catch (err) {
                    alert("Error: Invalid server response. Check console.");
                }
            };
        </script>
    @endpush

</x-admin-app-layout>

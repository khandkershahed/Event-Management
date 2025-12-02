<x-admin-app-layout :title="'Seat Map Designer'">

    <style>
        #designerCanvas {
            width: 100%;
            height: 650px;
            border: 2px dashed #d1d1d1;
            background: #fafafa;
        }
        .tool-btn {
            margin-right: 10px;
        }
    </style>

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

            <div class="mb-4">
                <button class="btn btn-light border tool-btn" id="addSectionBtn">
                    <i class="fas fa-layer-group me-1"></i> Add Section
                </button>

                <button class="btn btn-light border tool-btn" id="addSeatBtn">
                    <i class="fas fa-chair me-1"></i> Add Seat
                </button>

                <button class="btn btn-light border tool-btn" id="deleteBtn">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>

            <div id="designerCanvas"></div>

        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/konva@9/konva.min.js"></script>

    <script>
        let designJson = {!! $designJson !!};

        const stage = new Konva.Stage({
            container: 'designerCanvas',
            width: document.getElementById('designerCanvas').offsetWidth,
            height: 650,
        });

        const layer = new Konva.Layer();
        stage.add(layer);

        // Load saved shapes
        if (designJson.length > 0) {
            Konva.Node.create({ children: designJson }, stage);
        }

        // Add section button
        document.getElementById('addSectionBtn').onclick = function () {
            const group = new Konva.Group({
                x: 50,
                y: 50,
                draggable: true,
                name: 'section'
            });

            const rect = new Konva.Rect({
                width: 180,
                height: 100,
                fill: '#d0ebff',
                stroke: '#1c7ed6',
                strokeWidth: 2,
            });

            const text = new Konva.Text({
                text: 'New Section',
                fontSize: 16,
                x: 10,
                y: 10,
                fill: '#1c7ed6'
            });

            group.add(rect);
            group.add(text);
            layer.add(group);
            layer.draw();
        };

        // Add seat button
        document.getElementById('addSeatBtn').onclick = function () {
            const circle = new Konva.Circle({
                x: 60,
                y: 60,
                radius: 14,
                fill: '#fff',
                stroke: '#000',
                strokeWidth: 1,
                draggable: true,
                name: 'seat'
            });

            layer.add(circle);
            layer.draw();
        };

        // Delete button
        document.getElementById('deleteBtn').onclick = function () {
            const selected = stage.find('.selected')[0];
            if (selected) {
                selected.destroy();
                layer.draw();
            }
        };

        // Selection click handler
        stage.on('click', function (e) {
            stage.find('.selected').forEach(n => n.removeName('selected'));
            if (e.target !== stage) e.target.addName('selected');
        });

        // SAVE DESIGN
        document.getElementById('saveDesignBtn').onclick = function () {
            const json = layer.toJSON();

            fetch('{{ route("admin.seating-plans.save", $plan->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    design_json: json,
                    sections: window.__AUTO_EXTRACTED_SECTIONS ?? []
                })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            });
        };
    </script>
    @endpush

</x-admin-app-layout>

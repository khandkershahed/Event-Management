<x-admin-app-layout>
    <div class="card card-flash">
        <div class="card-header">

        </div>
        <div class="card-body">
            {{-- <h1>Edit Seat Map: {{ $seat_map->name }}</h1> --}}

            <div id="seat-map-editor" style="width: 100%; height: 600px; border: 1px solid #ccc;"></div>

            <button id="save-btn">Save Changes</button>


        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/konva@8.3.13/konva.min.js"></script>
        <script>
            const stageWidth = document.getElementById('seat-map-editor').clientWidth;
            const stageHeight = 600;

            const stage = new Konva.Stage({
                container: 'seat-map-editor',
                width: stageWidth,
                height: stageHeight,
            });

            const layer = new Konva.Layer();
            stage.add(layer);

            // Load background if exists
            @if ($seat_map->background_image)
                Konva.Image.fromURL('{{ asset($seat_map->background_image) }}', function(img) {
                    img.setAttrs({
                        x: 0,
                        y: 0,
                        width: stageWidth,
                        height: stageHeight,
                        listening: false,
                    });
                    layer.add(img);
                    layer.draw();
                });
            @endif

            // Helper to create a seat rect
            function createSeat(seat) {
                const group = new Konva.Group({
                    x: seat.x,
                    y: seat.y,
                    rotation: seat.rotation || 0,
                    draggable: true,
                    id: seat.id ? seat.id.toString() : null,
                });

                const rect = new Konva.Rect({
                    width: seat.width,
                    height: seat.height,
                    fill: seat.status === 'available' ? 'green' : seat.status === 'reserved' ? 'orange' : 'red',
                    stroke: 'black',
                    strokeWidth: 1,
                    cornerRadius: 4,
                });

                const text = new Konva.Text({
                    text: seat.label,
                    fontSize: 14,
                    fontFamily: 'Calibri',
                    fill: 'white',
                    align: 'center',
                    verticalAlign: 'middle',
                    width: seat.width,
                    height: seat.height,
                });

                group.add(rect);
                group.add(text);

                group.on('dragend', () => {
                    console.log('Moved seat', group.id(), group.x(), group.y());
                });

                group.on('click', () => {
                    const newLabel = prompt('Seat label:', seat.label);
                    if (newLabel) {
                        text.text(newLabel);
                        group.seatLabel = newLabel;
                    }
                });

                group.seatData = seat;

                return group;
            }

            // Load seats from backend
            const seats = @json($seat_map->seats);

            seats.forEach(seat => {
                const seatGroup = createSeat(seat);
                layer.add(seatGroup);
            });

            layer.draw();

            document.getElementById('save-btn').addEventListener('click', () => {
                const seatsToSave = [];
                layer.find('Group').each(group => {
                    const seatData = group.seatData;
                    seatsToSave.push({
                        id: group.id() ? parseInt(group.id()) : null,
                        label: group.seatLabel || seatData.label,
                        x: group.x(),
                        y: group.y(),
                        width: seatData.width,
                        height: seatData.height,
                        rotation: group.rotation(),
                        status: seatData.status,
                        price: seatData.price,
                        category: seatData.category,
                    });
                });

                fetch("", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            seats: seatsToSave
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) alert('Seats saved successfully!');
                        else alert('Error saving seats');
                    });
            });
        </script>
    @endpush
</x-admin-app-layout>

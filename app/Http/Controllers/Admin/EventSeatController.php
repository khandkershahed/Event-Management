<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventSeat;
use App\Models\EventType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EventSeatType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventSeatController extends Controller
{
    public function index()
    {
        return view('admin.pages.eventSeat.index', [
            'events' => Event::latest()->get(['id', 'name']),
        ]);
    }

    public function fetchSeatTypes(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $seatTypeIds = EventSeat::where('event_id', $request->event_id)
            ->distinct()
            ->pluck('seat_type_id');

        $seatTypes = EventSeatType::whereIn('id', $seatTypeIds)->get(['id', 'name']);

        return response()->json($seatTypes);
    }

    public function fetchSeats(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_type_id' => 'nullable|exists:event_seat_types,id',
        ]);

        $query = EventSeat::where('event_id', $request->event_id);

        if ($request->filled('seat_type_id')) {
            $query->where('seat_type_id', $request->seat_type_id);
        }

        $seats = $query->get();

        return response()->json($seats);
    }

    public function create()
    {
        $data = [
            'events' => Event::latest()->get(['id', 'name']),
            'seat_types' => EventSeatType::latest()->get(['id', 'name']),
        ];
        return view('admin.pages.eventSeat.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_type_id' => 'required|exists:event_seat_types,id',
            'price' => 'required|numeric|min:0',
            'bulk_seats' => 'required|string',
        ]);

        $lines = explode(PHP_EOL, trim($request->bulk_seats));
        $created = 0;

        foreach ($lines as $line) {
            $parts = array_map('trim', explode(',', $line));

            if (count($parts) !== 4) {
                continue; // Skip invalid lines
            }

            [$name, $row, $column, $code] = $parts;

            EventSeat::create([
                'event_id'     => $request->event_id,
                'seat_type_id' => $request->seat_type_id,
                'name'         => $name,
                'slug'         => Str::slug($name . '-' . uniqid()),
                'code'         => $code,
                'price'        => $request->price,
                'row'          => $row,
                'column'       => $column,
                'status'       => 'active',
                'added_by'     => Auth::guard('admin')->user()->id ?? 'system',
            ]);

            $created++;
        }

        return redirect()->route('admin.event-seat.index')->with('success', "$created seats created successfully.");
    }

    public function update(Request $request, $id)
    {
        $seat = EventSeat::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:220',
            'row'         => 'nullable|string|max:220',
            'column'      => 'nullable|string|max:220',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $seat->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'row'         => $request->row,
            'column'      => $request->column,
            'price'       => $request->price,
            'status'      => $request->status,
            'description' => $request->description,
            'updated_by'  => Auth::guard('admin')->user()->id ?? 'system',
        ]);

        return redirect()->route('admin.event-seat.index')->with('success', 'Seat updated successfully.');
    }
}

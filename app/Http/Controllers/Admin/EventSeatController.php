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
            'event_seats' => EventSeat::latest()->get(),
        ]);
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
        dd($request->all());
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
}

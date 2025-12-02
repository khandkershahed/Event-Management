<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use Illuminate\Http\Request;

class SeatingSeatController extends Controller
{
    /**
     * List all seats for a specific section.
     */
    public function index($sectionId)
    {
        $section = SeatingSection::with('plan.venue')->findOrFail($sectionId);

        return view('admin.pages.seating_seats.index', [
            'section' => $section,
            'seats'   => SeatingSeat::where('section_id', $sectionId)->orderBy('row_label')->orderBy('seat_number')->get(),
        ]);
    }

    /**
     * Show a single seat (for debugging/inspection only).
     */
    public function show($id)
    {
        $seat = SeatingSeat::with('section.plan.venue')->findOrFail($id);

        return view('admin.pages.seating_seats.show', [
            'seat' => $seat,
        ]);
    }

    /**
     * Disable create/edit/delete to avoid data corruption.
     */
    public function create()
    {
        abort(403, 'Seat creation is managed by the Seating Plan Designer.');
    }

    public function store(Request $request)
    {
        abort(403, 'Seat creation is managed by the Seating Plan Designer.');
    }

    public function edit($id)
    {
        abort(403, 'Seat editing is managed by the Seating Plan Designer.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Seat editing is managed by the Seating Plan Designer.');
    }

    public function destroy($id)
    {
        abort(403, 'Seat deletion is managed by the Seating Plan Designer.');
    }
}

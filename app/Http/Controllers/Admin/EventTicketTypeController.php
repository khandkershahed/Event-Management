<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventTicketTypeController extends Controller
{
    /**
     * Return all ticket types for an event (AJAX)
     */
    // manage
    public function manage($eventId) {
        $event = Event::findOrFail($eventId);

        return view('admin.pages.ticket_types.index', [
            'event' => $event,
        ]);
    }
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);

        $tickets = EventTicket::where('event_id', $eventId)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'event'  => $event,
            'tickets' => $tickets,
        ]);
    }

    /**
     * Return all seating sections for this event’s seating plan (AJAX)
     */
    public function sections($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->seating_plan_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'This event has no seating plan assigned.',
            ], 422);
        }

        $sections = SeatingSection::where('seating_plan_id', $event->seating_plan_id)
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'sections' => $sections,
        ]);
    }

    /**
     * Store new ticket type
     */
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'quantity'          => 'required|integer|min:1',
            'description'       => 'nullable|string',
            'section_ids'       => 'required|array|min:1',
            'section_ids.*'     => 'integer|exists:seating_sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'validation_error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            EventTicket::create([
                'event_id'          => $event->id,
                'name'              => $request->name,
                'price'             => $request->price,
                'quantity'          => $request->quantity,
                'description'       => $request->description,
                'valid_section_ids' => json_encode($request->section_ids),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket type created successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Error creating ticket type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update ticket type
     */
    public function update(Request $request, Event $event, EventTicket $ticket)
    {
        // $event = Event::findOrFail($event);
        // $ticket = EventTicket::where('event_id', $event)->findOrFail($ticketId);

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'quantity'          => 'required|integer|min:1',
            'description'       => 'nullable|string',
            'section_ids'       => 'required|array|min:1',
            'section_ids.*'     => 'integer|exists:seating_sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'validation_error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $ticket->update([
                'name'              => $request->name,
                'price'             => $request->price,
                'quantity'          => $request->quantity,
                'description'       => $request->description,
                'valid_section_ids' => json_encode($request->section_ids),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket type updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete ticket type
     */
    public function destroy($eventId, $ticketId)
    {
        $ticket = EventTicket::where('event_id', $eventId)
            ->findOrFail($ticketId);

        try {
            $ticket->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket type deleted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

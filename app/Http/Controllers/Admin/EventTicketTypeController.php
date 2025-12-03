<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventTicketTypeController extends Controller
{
    /**
     * Manage page (Blade view)
     */
    public function manage($eventId)
    {
        $event = Event::select('id', 'name', 'seating_plan_id')->findOrFail($eventId);

        $tickets = EventTicket::where('event_id', $eventId)
            ->orderBy('id', 'DESC')
            ->get();

        // Load sections belonging to the event’s seating plan
        $sections = [];
        if ($event->seating_plan_id) {
            $sections = SeatingSection::where('seating_plan_id', $event->seating_plan_id)
                ->orderBy('name')
                ->get();
        }

        return view('admin.pages.ticket_types.index', [
            'event'        => $event,
            'ticketTypes'  => $tickets,
            'sections'     => $sections,
        ]);
    }

    /**
     * AJAX List Tickets
     */
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);

        $tickets = EventTicket::where('event_id', $eventId)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status'  => 'success',
            'event'   => $event,
            'tickets' => $tickets,
        ]);
    }

    /**
     * AJAX — Get Seating Sections for event’s plan
     */
    public function sections($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->seating_plan_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This event has no seating plan assigned.',
            ], 422);
        }

        $sections = SeatingSection::where('seating_plan_id', $event->seating_plan_id)
            ->orderBy('name')
            ->get();

        return response()->json([
            'status'    => 'success',
            'sections'  => $sections,
        ]);
    }

    /**
     * Store NEW Ticket Type
     */
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $validator = Validator::make($request->all(), [
            'name'                        => 'required|string|max:255',
            'price'                       => 'nullable|numeric|min:0',
            'quantity'                    => 'nullable|integer|min:1',
            'description'                 => 'nullable|string',

            'section_ids'                 => 'required|array|min:1',
            'section_ids.*'               => 'integer|exists:seating_sections,id',

            'is_active'                   => 'nullable|boolean',
            'min_per_order'               => 'nullable|integer|min:1',
            'max_per_order'               => 'nullable|integer|min:1',

            'early_bird_ends_at'          => 'nullable|date',

            // Fee Engine
            'platform_fee_fixed'          => 'nullable|numeric|min:0',
            'platform_fee_percent'        => 'nullable|numeric|min:0|max:100',

            'processing_fee_fixed'        => 'nullable|numeric|min:0',
            'processing_fee_percent'      => 'nullable|numeric|min:0|max:100',

            'payment_gateway_fee_fixed'   => 'nullable|numeric|min:0',
            'payment_gateway_fee_percent' => 'nullable|numeric|min:0|max:100',

            'fee_customer_percent'        => 'nullable|integer|min:0|max:100',
            'fee_organizer_percent'       => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            EventTicket::create([
                'event_id'                    => $event->id,
                'name'                        => $request->name,
                'price'                       => $request->price ?? 0,
                'quantity'                    => $request->quantity,
                'description'                 => $request->description,

                'valid_section_ids'           => json_encode($request->section_ids),

                'is_active'                   => $request->is_active ?? true,
                'min_per_order'               => $request->min_per_order ?? 1,
                'max_per_order'               => $request->max_per_order,

                'early_bird_ends_at'          => $request->early_bird_ends_at,

                // Fee Engine
                'platform_fee_fixed'          => $request->platform_fee_fixed ?? 0,
                'platform_fee_percent'        => $request->platform_fee_percent ?? 0,

                'processing_fee_fixed'        => $request->processing_fee_fixed ?? 0,
                'processing_fee_percent'      => $request->processing_fee_percent ?? 0,

                'payment_gateway_fee_fixed'   => $request->payment_gateway_fee_fixed ?? 0,
                'payment_gateway_fee_percent' => $request->payment_gateway_fee_percent ?? 0,

                // Fee split (Stripe-B)
                'fee_customer_percent'        => $request->fee_customer_percent ?? 100,
                'fee_organizer_percent'       => $request->fee_organizer_percent ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
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
     * Show ticket data for edit modal
     */
    public function show(Event $event, EventTicket $ticket)
    {
        return response()->json([
            'status' => 'success',
            'ticket' => $ticket,
        ]);
    }


    /**
     * UPDATE Ticket Type
     */
    public function update(Request $request, Event $event, EventTicket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'name'                        => 'required|string|max:255',
            'price'                       => 'nullable|numeric|min:0',
            'quantity'                    => 'nullable|integer|min:1',
            'description'                 => 'nullable|string',

            'section_ids'                 => 'required|array|min:1',
            'section_ids.*'               => 'integer|exists:seating_sections,id',

            'is_active'                   => 'nullable|boolean',
            'min_per_order'               => 'nullable|integer|min:1',
            'max_per_order'               => 'nullable|integer|min:1',

            'early_bird_ends_at'          => 'nullable|date',

            // Fee Engine
            'platform_fee_fixed'          => 'nullable|numeric|min:0',
            'platform_fee_percent'        => 'nullable|numeric|min:0|max:100',

            'processing_fee_fixed'        => 'nullable|numeric|min:0',
            'processing_fee_percent'      => 'nullable|numeric|min:0|max:100',

            'payment_gateway_fee_fixed'   => 'nullable|numeric|min:0',
            'payment_gateway_fee_percent' => 'nullable|numeric|min:0|max:100',

            'fee_customer_percent'        => 'nullable|integer|min:0|max:100',
            'fee_organizer_percent'       => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $ticket->update([
                'name'                        => $request->name,
                'price'                       => $request->price ?? 0,
                'quantity'                    => $request->quantity,
                'description'                 => $request->description,

                'valid_section_ids'           => json_encode($request->section_ids),

                'is_active'                   => $request->is_active ?? true,
                'min_per_order'               => $request->min_per_order ?? 1,
                'max_per_order'               => $request->max_per_order,

                'early_bird_ends_at'          => $request->early_bird_ends_at,

                // Fee Engine
                'platform_fee_fixed'          => $request->platform_fee_fixed ?? 0,
                'platform_fee_percent'        => $request->platform_fee_percent ?? 0,

                'processing_fee_fixed'        => $request->processing_fee_fixed ?? 0,
                'processing_fee_percent'      => $request->processing_fee_percent ?? 0,

                'payment_gateway_fee_fixed'   => $request->payment_gateway_fee_fixed ?? 0,
                'payment_gateway_fee_percent' => $request->payment_gateway_fee_percent ?? 0,

                'fee_customer_percent'        => $request->fee_customer_percent ?? 100,
                'fee_organizer_percent'       => $request->fee_organizer_percent ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
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
     * DELETE Ticket Type
     */
    public function destroy($eventId, $ticketId)
    {
        $ticket = EventTicket::where('event_id', $eventId)->findOrFail($ticketId);

        try {
            $ticket->delete();

            return response()->json([
                'status'  => 'success',
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

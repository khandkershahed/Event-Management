<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\SeatingPlan;
use App\Services\Seating\SeatingPlanDesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SeatingPlanDesignerController extends Controller
{
    public function __construct(private readonly SeatingPlanDesignService $designService)
    {
    }

    public function show(Request $request, SeatingPlan $seating_plan): View
    {
        $this->ensureOwnPlan($request, $seating_plan);
        $this->designService->assertCanRebuildInventory($seating_plan);

        $seating_plan->loadMissing('venue');

        return view('organizer.seating-plans.designer', [
            'plan' => $seating_plan,
            'venue' => $seating_plan->venue,
            'designJson' => $seating_plan->design_json ? json_encode($seating_plan->design_json) : '[]',
        ]);
    }

    public function save(Request $request, SeatingPlan $seating_plan): JsonResponse|RedirectResponse
    {
        $this->ensureOwnPlan($request, $seating_plan);

        $request->validate([
            'design_json' => ['required'],
            'sections' => ['nullable'],
        ]);

        try {
            $this->designService->saveDesign(
                $seating_plan,
                $request->input('design_json'),
                $request->input('sections')
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Seat map saved and seating inventory rebuilt successfully.',
                ]);
            }

            return redirect()
                ->route('organizer.seating-plans.designer', $seating_plan)
                ->with('success', 'Seat map saved and seating inventory rebuilt successfully.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Organizer seatmap save error: '.$exception->getMessage(), [
                'seating_plan_id' => $seating_plan->id,
                'exception' => $exception,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Save failed: '.$exception->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Save failed: '.$exception->getMessage());
        }
    }

    private function ensureOwnPlan(Request $request, SeatingPlan $plan): void
    {
        $profile = $request->user()->organizerProfile;

        abort_unless($profile && (int) $plan->organizer_profile_id === (int) $profile->id, 403);
    }
}

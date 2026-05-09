<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * Route-compatible method for the admin designer page.
     */
    public function show($id): View
    {
        return $this->designer($id);
    }

    /**
     * Show the seat map designer interface.
     */
    public function designer($id): View
    {
        $plan = SeatingPlan::with('venue')->findOrFail($id);

        return view('admin.pages.seating_plans.designer', [
            'plan' => $plan,
            'venue' => $plan->venue,
            'designJson' => $plan->design_json ? json_encode($plan->design_json) : '[]',
        ]);
    }

    /**
     * Save visual designer JSON and rebuild seating_sections / seating_seats through the shared service.
     */
    public function save(Request $request, $id): JsonResponse|RedirectResponse
    {
        $plan = SeatingPlan::findOrFail($id);

        $request->validate([
            'design_json' => ['required'],
            'sections' => ['nullable'],
        ]);

        try {
            $this->designService->saveDesign(
                $plan,
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
                ->route('admin.seating-plans.designer', $plan->id)
                ->with('success', 'Seat map saved and seating inventory rebuilt successfully.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Admin seatmap save error: '.$exception->getMessage(), [
                'seating_plan_id' => $plan->id,
                'exception' => $exception,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Save failed: '.$exception->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error saving map: '.$exception->getMessage());
        }
    }
}

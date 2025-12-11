<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Services\StreakService;
use Illuminate\Http\Request;
use App\Events\GoalCompleted;


class GoalController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    /**
     * Display a listing of the user's goals.
     */
    public function index()
    {
        // FIX 1: Don't show 'archived' goals in the main list
        // Also assuming you want to see YOUR goals only
        $goals = Goal::where('user_id', auth()->id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->get();

        // Pass 'isArchived' => false so the view knows these are active goals
        return view('goals.index', compact('goals'))->with('isArchived', false);
    }

    /**
     * Show the form for creating a new goal.
     */
    public function create()
    {
        return view('goals.create');
    }

    /**
     * Store a newly created goal in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        $data = $request->validated();

        // ✅ REQUIRED by your DB schema
        $data['user_id'] = auth()->id();

        // ✅ Safe defaults (your DB also supports these)
        $data['current_value'] = 0;
        $data['status'] = 'active';
        $data['unit'] = $data['unit'] ?? 'count';

        $goal = Goal::create($data);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal created successfully!');
    }



    /**
     * Display the specified goal.
     */
    public function show(Goal $goal)
    {
        // Ensure the user owns the goal
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        // Load sub-tasks ordered by creation (so new ones appear at bottom)
        $goal->load(['subTasks' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return view('goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified goal.
     */
    public function edit(Goal $goal)
    {

        return view('goals.edit', [
            'goal' => $goal,
        ]);
    }

    /**
     * Update the specified goal in storage.
     */
    public function update(Request $request, Goal $goal)
    {
        // 1. Validation
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'sometimes|integer|min:1',
            'current_value' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:active,completed,archived',
        ]);

        // 2. Update the goal with new values
        $goal->fill($validated);

        // 3. AUTO-COMPLETE LOGIC
        // If progress reaches the target, mark as completed automatically
        if ($goal->current_value >= $goal->target_value && $goal->status !== 'completed') {
            $goal->status = 'completed';
            $goal->completed_at = now();

            // SAVE FIRST to ensure DB has the correct status
            $goal->save();

            // FIRE THE EVENT (Awards Badges)
            GoalCompleted::dispatch($goal);

            return back()->with('success', 'Goal Completed! Badge progress updated! 🚀');
        }

        $goal->save();

        return back()->with('success', 'Goal updated successfully.');
    }

    /**
     * Remove the specified goal from storage (soft delete).
     */
    public function destroy(Goal $goal)
    {
        // This performs a soft delete if you added the SoftDeletes trait to the model
        $goal->delete();

        return back()->with('success', 'Goal removed successfully.');
    }

    /**
     * Mark goal as complete
     */
    public function complete(Goal $goal)
    {

        if ($goal->markAsComplete()) {
            $this->streakService->updateStreak(auth()->user());

            return back()->with('success', '🎉 Goal completed! Streak updated.');
        }

        // Example logic
        $goal->update(['status' => 'completed', 'completed_at' => now()]);

        // FIRE THE EVENT HERE
        GoalCompleted::dispatch($goal);

        return back()->with('success', 'Goal updated!');
        // return back()->with('error', 'Failed to complete goal.');
    }

    // 1. Hide / Archive
    public function archive(Goal $goal)
    {
        // FIX 2: Set status to 'archived' (Soft hide)
        $goal->update(['status' => 'archived']);

        return back()->with('success', 'Goal hidden from list.');
    }

    // Show the list of hidden/archived goals
    public function archived()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->where('status', 'archived') // Only get the hidden ones
            ->latest()
            ->get();

        // We reuse the index view but pass a flag 'isArchived'
        return view('goals.index', compact('goals'))->with('isArchived', true);
    }

    // Restore an archived goal to the active list (without resetting progress)
    public function restore(Goal $goal)
    {
        $goal->update(['status' => 'active']);

        return back()->with('success', 'Goal restored to your list!');
    }

    // 2. Reopen
    public function reopen(Goal $goal)
    {
        // FIX 3: Reset progress to 0 so the progress bar drops back
        // If you want to keep progress, you must change the Blade logic instead.
        // For now, restarting the goal makes the most sense.
        $goal->update([
            'status' => 'active',
            'current_value' => 0, // <--- Reset progress so it looks "Active"
            'completed_at' => null
        ]);

        return back()->with('success', 'Goal reopened and reset!');
    }

    // 3. Mark as Done
    public function markDone(Goal $goal)
    {
        $goal->update([
            'status' => 'completed',
            'current_value' => $goal->target_value, // Force 100%
            'completed_at' => now()
        ]);

        // Dispatch Event for Badges
        \App\Events\GoalCompleted::dispatch($goal);

        return back()->with('success', 'Goal completed! Great job! 🎉');
    }

    // 4. Increment (Add +1)
    public function increment(Request $request, Goal $goal)
    {
        $amount = $request->input('current_amount', 1);
        $goal->increment('current_value', $amount);

        // Auto-complete check
        if ($goal->current_value >= $goal->target_value) {
            $this->markDone($goal); // Call the complete function
        }

        return back()->with('success', 'Progress updated.');
    }

    /**
     * Batch complete multiple goals
     */
    public function batchComplete(Request $request)
    {
        $request->validate([
            'goal_ids' => 'required|array',
            'goal_ids.*' => 'exists:goals,id',
        ]);

        $goals = Goal::whereIn('id', $request->goal_ids)
            ->where('user_id', auth()->id())
            ->get();

        $completedCount = 0;
        foreach ($goals as $goal) {
            if ($goal->markAsComplete()) {
                $completedCount++;
            }
        }

        if ($completedCount > 0) {
            $this->streakService->updateStreak(auth()->user());
        }

        return back()->with('success', "{$completedCount} goal(s) completed!");
    }
}

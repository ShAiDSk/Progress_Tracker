<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Goal;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    // Add a new sub-task to a goal
    public function store(Request $request, Goal $goal)
    {
        // Security check: Ensure the user owns this goal
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $goal->subTasks()->create([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Sub-task added!');
    }

    // Toggle complete/incomplete (API for AlpineJS)
    public function toggle(SubTask $subTask)
    {
        // Security check: Ensure the user owns the goal associated with this task
        if ($subTask->goal->user_id !== auth()->id()) {
            abort(403);
        }

        // Flip the boolean
        $subTask->update([
            'is_completed' => !$subTask->is_completed
        ]);

        return response()->json([
            'success' => true, 
            'is_completed' => $subTask->is_completed
        ]);
    }
    
    // Delete a sub-task
    public function destroy(SubTask $subTask)
    {
         if ($subTask->goal->user_id !== auth()->id()) {
            abort(403);
        }
        
        $subTask->delete();
        
        return back()->with('success', 'Sub-task removed.');
    }
}
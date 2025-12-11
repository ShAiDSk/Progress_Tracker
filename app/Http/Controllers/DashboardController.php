<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Get stats (Efficient Database Counts)
        // We filter out 'archived' for the top cards so they match your Goals list
        $activeGoals = Goal::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        $completedGoals = Goal::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        
        // 2. HEATMAP DATA LOGIC
        // We look for ANY goal with a completion date, even if archived
        $activities = Goal::where('user_id', $userId)
            ->whereNotNull('completed_at') // ✅ KEY CHANGE: Trust the date, not the status
            ->whereDate('completed_at', '>=', Carbon::now()->subYear())
            ->get()
            ->groupBy(function($goal) {
                return Carbon::parse($goal->completed_at)->format('Y-m-d');
            });

        // 3. Generate the 365-day grid
        $heatmapData = [];
        $endDate = Carbon::now()->endOfDay(); // Ensure we capture today fully
        $startDate = Carbon::now()->subYear()->startOfDay();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            
            // Check counts
            $count = isset($activities[$dateString]) ? $activities[$dateString]->count() : 0;
            
            $heatmapData[] = [
                'date' => $dateString,
                'count' => $count,
                'level' => $this->getIntensityLevel($count),
            ];
        }

        return view('dashboard', compact('activeGoals', 'completedGoals', 'heatmapData'));
    }

    // Helper: 0-4 Intensity Scale
    private function getIntensityLevel($count)
    {
        if ($count == 0) return 0; // Gray
        if ($count == 1) return 1; // Light Green
        if ($count <= 3) return 2; // Medium Green
        if ($count <= 5) return 3; // Dark Green
        return 4; // Deep Green (Max)
    }
}
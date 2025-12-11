<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\GoalCompletedEmail;
use App\Events\GoalCompleted;
use App\Models\Achievement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForAchievements
{
    public function handle(GoalCompleted $event)
    {
        // 🔍 DEBUG LINE: Check if this runs
        Log::info('✅ EVENT FIRED! Listener is running for Goal: ' . $event->goal->title);
        $user = $event->goal->user;
        
        // 1. Send the Email 📧
        try {
            Mail::to($user->email)->send(new GoalCompletedEmail($event->goal, $user));
        } catch (\Exception $e) {
            // Log error if mail fails, but don't crash the app
            logger()->error("Failed to send email: " . $e->getMessage());
        }

        // 2. Achievement Logic
        $completedCount = $user->goals()->where('status', 'completed')->count();

        // "First Steps" - Award if they have AT LEAST 1 completed goal
        if ($completedCount >= 1) {
            $this->unlock($user, 'First Steps');
        }

        // "Goal Crusher" - Award if they have AT LEAST 10
        if ($completedCount >= 10) {
            $this->unlock($user, 'Goal Crusher');
        }

        // "Early Bird" - Completed a goal before 8 AM
        $completionTime = Carbon::parse($event->goal->completed_at);
        if ($completionTime->format('H') < 8) {
            $this->unlock($user, 'Early Bird');
        }

        // "On Fire" - Check Streak Table
        $userStreak = $user->streak; 
        if ($userStreak && $userStreak->current_streak >= 3) {
            $this->unlock($user, 'On Fire');
        }
    }

    // Helper function to safely attach badge
    private function unlock($user, $badgeName)
    {
        $badge = Achievement::where('name', $badgeName)->first();

        if ($badge && !$user->achievements->contains($badge->id)) {
            $user->achievements()->attach($badge);
            session()->flash('achievement_unlocked', $badge->name);
        }
    }
}
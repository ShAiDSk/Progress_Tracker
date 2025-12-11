<?php
namespace App\Events;

use App\Models\Goal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoalCompleted
{
    use Dispatchable, SerializesModels;

    public $goal;

    // Pass the goal when the event is created
    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }
}
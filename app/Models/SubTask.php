<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $fillable = ['goal_id', 'title', 'is_completed'];

    // Relationship: A subtask belongs to one Goal
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
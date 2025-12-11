<?php

namespace App\Mail;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GoalCompletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $goal;
    public $user;

    public function __construct(Goal $goal, User $user)
    {
        $this->goal = $goal;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Goal Crushed: ' . $this->goal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.goals.completed', // We will create this view next
        );
    }
}

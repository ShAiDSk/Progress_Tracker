<x-mail::message>
# Congratulations, {{ $user->name }}! 🚀

You just smashed your goal: **{{ $goal->title }}**.

## Stats:
- **Target Reached:** {{ $goal->target_value }}
- **Completed On:** {{ $goal->completed_at->format('F d, Y') }}

Keep up the momentum!

<x-mail::button :url="route('dashboard')">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
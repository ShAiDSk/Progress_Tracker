<x-app-layout>

    <style>
        /* Page Gradient Background */
        body {
            background: radial-gradient(circle at top left, #0f172a, #020617 60%);
            background-attachment: fixed;
        }

        /* Neon Title Animation */
        .neon-title {
            text-shadow: 0 0 12px #3b82f6, 0 0 24px #60a5fa;
            animation: glowPulse 2.5s infinite ease-in-out;
        }

        @keyframes glowPulse {
            0%, 100% { text-shadow: 0 0 10px #3b82f6; }
            50% { text-shadow: 0 0 25px #60a5fa; }
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
        }

        /* Smooth Progress Bar */
        .progress-inner {
            transition: width 0.8s ease-in-out;
        }
    </style>

    <div class="max-w-6xl mx-auto px-6 py-12">

        <h2 class="text-4xl font-extrabold text-white neon-title mb-10 tracking-wide">
            Dashboard
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-300 text-sm mb-1">Total Goals</p>
                <h3 class="text-4xl font-bold text-white">
                    {{ Auth::user()->goals->where('status', '!=', 'archived')->count() }}
                </h3>
            </div>

            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-300 text-sm mb-1">Active Goals</p>
                <h3 class="text-4xl font-bold text-white">
                    {{ Auth::user()->goals->where('status', 'active')->count() }}
                </h3>
            </div>

            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-300 text-sm mb-1">Completed</p>
                <h3 class="text-4xl font-bold text-white">
                    {{ Auth::user()->goals->where('status', 'completed')->count() }}
                </h3>
            </div>

        </div>

        <div class="py-12">
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Activity Log (Last 365 Days)</h3>

                    <div class="flex flex-col overflow-x-auto">
                        <div class="inline-grid grid-rows-7 grid-flow-col gap-1 min-w-max pb-2">
                            @foreach ($heatmapData as $day)
                            <div title="{{ $day['date'] }}: {{ $day['count'] }} goals completed"
                                class="w-3 h-3 rounded-sm 
                                @if($day['level'] == 0) bg-gray-200 dark:bg-gray-700 
                                @elseif($day['level'] == 1) bg-green-200 dark:bg-green-900
                                @elseif($day['level'] == 2) bg-green-400 dark:bg-green-700
                                @elseif($day['level'] == 3) bg-green-600 dark:bg-green-500
                                @elseif($day['level'] == 4) bg-green-800 dark:bg-green-400
                                @endif"></div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end text-xs text-gray-500 dark:text-gray-400 gap-2 mt-2">
                            <span>Less</span>
                            <div class="w-3 h-3 bg-gray-200 dark:bg-gray-700 rounded-sm"></div>
                            <div class="w-3 h-3 bg-green-200 dark:bg-green-900 rounded-sm"></div>
                            <div class="w-3 h-3 bg-green-400 dark:bg-green-700 rounded-sm"></div>
                            <div class="w-3 h-3 bg-green-600 dark:bg-green-500 rounded-sm"></div>
                            <div class="w-3 h-3 bg-green-800 dark:bg-green-400 rounded-sm"></div>
                            <span>More</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">🏆 Achievements</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse(auth()->user()->achievements as $badge)
                    <div class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-gray-700 border border-yellow-200 dark:border-yellow-600 rounded-lg text-center">
                        <span class="text-4xl mb-2">{{ $badge->icon }}</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $badge->name }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">{{ $badge->description }}</p>
                        <span class="text-[10px] text-gray-400 mt-2">
                            {{ \Carbon\Carbon::parse($badge->pivot->unlocked_at)->diffForHumans() }}
                        </span>
                    </div>
                    @empty
                    <p class="col-span-4 text-gray-500 italic">No badges yet. Keep crushing goals!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-14">
            <h3 class="text-2xl font-semibold text-white mb-5">Recent Goals</h3>

            <div class="space-y-5">
                @forelse (Auth::user()->goals->where('status', 'active')->take(3) as $goal)
                <div class="glass-card p-6 rounded-xl">
                    <div class="flex justify-between">
                        <h4 class="text-lg font-semibold text-white">{{ $goal->title }}</h4>
                        <span class="text-gray-300 text-sm">
                            {{ $goal->deadline ?? 'No deadline' }}
                        </span>
                    </div>

                    <p class="text-gray-300 mt-1">{{ $goal->description }}</p>

                    <p class="text-sm text-blue-300 mt-4 font-medium">
                        Progress: {{ $goal->current_value }} / {{ $goal->target_value ?? '-' }}
                    </p>

                    @php
                    $p = ($goal->target_value && $goal->target_value > 0)
                        ? ($goal->current_value / $goal->target_value) * 100
                        : 0;
                    @endphp

                    <div class="w-full bg-gray-700 h-2 rounded-full mt-2 overflow-hidden">
                        <div class="progress-inner bg-blue-500 h-2 rounded-full" style="width: {{ $p }}%;"></div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400">No goals yet. Start creating your first goal.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
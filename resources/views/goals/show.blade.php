<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Goal Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $goal->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $goal->description }}</p>
                            
                            <div class="flex gap-2 mb-4">
                                <span class="px-2 py-1 bg-gray-700 rounded text-xs text-white">{{ $goal->category ?? 'General' }}</span>
                                <span class="px-2 py-1 bg-blue-900 rounded text-xs text-white uppercase">{{ $goal->priority ?? 'Medium' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-bold text-blue-500">{{ $goal->current_value }}</span>
                            <span class="text-gray-400">/ {{ $goal->target_value }}</span>
                        </div>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                        @php $percent = ($goal->current_value / $goal->target_value) * 100; @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($percent, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Checklist / Sub-tasks
                    </h3>

                    <div class="space-y-3 mb-6">
                        @forelse($goal->subTasks as $task)
                            <div x-data="{ completed: {{ $task->is_completed ? 'true' : 'false' }} }"
                                 class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" :checked="completed"
                                        @change="completed = !completed; fetch('{{ route('subtasks.toggle', $task->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' } })"
                                        class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500">
                                    <span :class="{ 'line-through text-gray-500': completed, 'text-gray-900 dark:text-gray-100': !completed }">
                                        {{ $task->title }}
                                    </span>
                                </div>

                                <form action="{{ route('subtasks.destroy', $task->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700">&times;</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No sub-tasks yet.</p>
                        @endforelse
                    </div>

                    <form action="{{ route('subtasks.store', $goal->id) }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="text" name="title" placeholder="Add a step..." required
                               class="flex-1 rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
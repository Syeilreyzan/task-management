<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component
{
    public $projectId;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    #[On('refreshTeams')]
    public function refresh(){}

    public function with(): array
    {
        $project = Project::with('teams')
                    ->findOrFail($this->projectId);

        $existingUserIds = $project
                            ->teams
                            ->pluck('user_id')
                            ->toArray();

        $existingUserIds[] = auth()->id();

        return [
            'project' => Project::with('teams')->find($this->projectId),
            'users' => User::whereNotIn('id', $existingUserIds)->get()
        ];
    }
};
?>

<div class="w-full mx-auto p-3 lg:p-6 border-accent-foreground bg-surface rounded-xl border shadow-sm space-y-3">
    <div class="flex justify-between items-center gap-1">
        <p class="text-lg font-semibold">Project Members</p>

        @can('manage-projects')
            <livewire:tasks.modal.add-new-members :projectId="$projectId" />
        @endcan
    </div>

    <ul class="space-y-2">
        @if ($project->teams->isNotEmpty())
            <ul class="space-y-3">
                @foreach ($project->teams as $user)
                    <li class="flex items-center gap-3 p-2 rounded-xl border bg-accent-foreground dark:bg-zinc-700 dark:hover:bg-zinc-800 hover:border-blue-600! transition-colors">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm font-semibold text-blue-700 uppercase">
                            {{ substr($user->name, 0, 2) }}
                        </div>

                        <div>
                            <flux:text class="font-medium text-txt-main capitalize">{{ $user->name }}</flux:text>
                            <flux:text class="text-sm text-txt-muted">{{ $user->email }}</flux:text>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-txt-muted">No members assigned to this project yet.</p>
        @endif
    </ul>
</div>

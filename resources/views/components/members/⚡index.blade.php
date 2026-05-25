<?php

use Livewire\Component;

new class extends Component
{
    public $projects = [];

    public function mount($projects = [])
    {
        $this->projects = $projects;
    }
};
?>

<div>
    @forelse ($projects as $project)
        <div class="rounded-lg border border-surface bg-accent-foreground p-2 lg:p-4 hover:border-blue-600 transition-colors shadow-sm">
            <div class="flex justify-between items-center">
                <div class="flex flex-col gap-0">
                    <flux:text class="text-lg font-bold">{{ $project->name }}hello</flux:text>
                    <flux:text class="text-xs font-normal text-txt-muted">created {{ $project->created_at->diffForHumans() }}</flux:text>
                </div>

                <a href="{{ route('projects.show', $project->id) }}" class="ml-4">
                    <div class="hidden lg:flex!">
                        <flux:button variant="outline" size="sm" class="items-center border-gray-400! text-blue-600 hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer">
                            View Details
                        </flux:button>
                    </div>

                    <div class="flex lg:hidden!">
                        <flux:button icon="eye" variant="outline" size="sm" class="items-center border-gray-400! text-blue-600 hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer" />
                    </div>
                </a>
            </div>

            <div class="mt-3">
                @if($project->teams->isNotEmpty())
                    <div class="flex justify-between">
                        <ul class="space-y-1.5">
                            @foreach($project->teams as $member)
                                <li class="flex items-center gap-2 text-sm text-gray-700 bg-gray-200 dark:bg-zinc-600  px-2.5 py-1 rounded-md border border-gray-100">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[10px] font-bold uppercase">
                                        {{ substr($member->name, 0, 2) }}
                                    </div>

                                    <flux:text class="capitalize text-txt-main">{{ $member->name }}</flux:text>
                                    <flux:text class="text-[10px] text-txt-muted ml-auto">({{ $member->email }})</flux:text>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-xs italic text-txt-muted">No team members assigned to this workspace yet.</p>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-8 border border-dashed rounded-xl bg-accent-foreground dark:bg-zinc-700">
            <p class="text-sm text-txt-muted">You are not currently assigned to any projects.</p>
        </div>
    @endforelse
</div>

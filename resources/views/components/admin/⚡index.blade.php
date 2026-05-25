<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component
{
    public $deleteUserId;
    public $projects = [];
    public $memberProjects = [];
    public $users = [];

    public function mount($projects = [], $memberProjects = [], $users = [])
    {
        $this->projects = $projects;
        $this->memberProjects = $memberProjects;
        $this->users = $users;
    }

    public function deleteUser($id)
    {
        $this->deleteUserId = $id;
        Flux::modal('delete-user-modal')->show();
    }

    public function confirmDeleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->projects()->detach();
        $user->delete();

        Flux::toast('User removed successfully!');
        $this->dispatch('refreshUsers');
        Flux::modal('delete-user-modal')->close();
        $this->reset('deleteUserId');
    }
};
?>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-y-4 lg:gap-6">
        <!-- Projects List -->
        <div class="col-span-2 flex flex-col gap-4 p-4 rounded-xl border border-accent-foreground bg-surface">
            <div class="flex justify-between items-center">
                <p class="text-xl font-semibold">List of Projects</p>
                <livewire:projects.modal.add-project-modal />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                @if ($projects->isNotEmpty())
                    @foreach ($projects as $project)
                        <div class="rounded-lg border border-gray-400 p-4 hover:border-blue-600 transition-colors bg-accent-foreground dark:bg-zinc-700 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col gap-0">
                                    <flux:text class="text-lg font-bold">{{ $project->name }}</flux:text>
                                    <flux:text class="text-xs font-normal">created {{ $project->created_at->diffForHumans() }}</flux:text>
                                </div>

                                <a href="{{ route('projects.show', $project->id) }}" class="ml-4">
                                    <flux:button variant="outline" size="sm" class="hidden! lg:flex! items-center border-gray-400! text-blue-600 hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer">
                                        View Details
                                    </flux:button>
                                    <flux:button variant="outline" size="sm" class="hidden lg:hidden! items-center border-gray-400! text-blue-600 hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </flux:button>
                                </a>
                            </div>

                            <div class="mt-3">
                                @if($project->teams->isNotEmpty())
                                    <div class="flex justify-between">
                                        <ul class="space-y-1.5">
                                            @foreach($project->teams as $member)
                                                <li class="flex items-center gap-2 text-sm text-txt-main bg-gray-200 dark:bg-zinc-600 px-2.5 py-1 rounded-md border border-gray-100">
                                                    <div class="h-6 w-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[10px] font-bold uppercase">
                                                        {{ substr($member->name, 0, 2) }}
                                                    </div>

                                                    <span class="capitalize text-txt-muted">{{ $member->name }}</span>
                                                    <span class="text-[10px] text-txt-muted ml-auto">({{ $member->email }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <p class="text-xs italic text-gray-400">No team members assigned to this workspace yet.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-txt-muted">No projects available.</p>
                @endif
            </div>
        </div>

        <!-- Team Members List -->
        <div class="col-span-1 flex flex-col gap-4 p-4 rounded-xl border border-accent-foreground bg-surface">
            <div class="flex justify-between items-center">
                <p class="text-xl font-bold text-txt-main">List of Team Members</p>
                <livewire:modal.add-new-user />
            </div>

            <div class="grid grid-cols-1 gap-2 w-full">
                @if ($users->isNotEmpty())
                    @foreach ($users as $member)
                        <div class="rounded-xl border p-2 shadow-sm hover:shadow-md hover:border-blue-600 transition-colors flex items-center justify-between {{ $member->is(auth()->user()) ? 'bg-blue-100 dark:bg-blue-900 border-blue-600' : ' bg-accent-foreground dark:bg-zinc-700' }}">
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center font-bold text-sm uppercase">
                                    {{ substr($member->name, 0, 2) }}
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-txt-main capitalize">{{ $member->name }}</h2>
                                    <p class="text-sm text-txt-muted">{{ $member->email }}</p>
                                </div>

                                @if ($member->is(auth()->user()))
                                    <div>
                                        <flux:badge color="blue" size="sm" class="capitalize">you</flux:badge>
                                    </div>
                                @endif
                            </div>

                            @if (!$member->is(auth()->user()))
                                <flux:tooltip content="Delete user" placement="top">
                                    <flux:button variant="outline" size="sm"
                                        class="ml-auto flex items-center border-gray-400! hover:text-red-600 hover:border-red-600! transition-colors cursor-pointer"
                                        wire:click="deleteUser({{ $member->id }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </flux:button>
                                </flux:tooltip>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 text-center py-8 border border-dashed rounded-xl bg-accent-foreground dark:bg-zinc-70">
                        <p class="text-sm text-txt-muted">No team members found in your projects.
                            <livewire:modal.add-new-user />
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!--delete modal -->
        <flux:modal name="delete-user-modal" class="md:w-96">
            <form wire:submit="confirmDeleteUser({{ $this->deleteUserId }})" class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete User</flux:heading>
                </div>

                <div class="flex gap-4">
                    <flux:spacer />
                    <flux:button variant="danger" type="submit" size="sm" class="border-red-500! hover:text-red-700 hover:border-red-700! transition-colors">
                        Delete
                    </flux:button>

                    <flux:button variant="outline" size="sm"
                        x-on:click="$flux.modal('delete-user-modal').close()"
                        class="border-gray-400! hover:text-gray-600 hover:border-gray-600! transition-colors">
                        Cancel
                    </flux:button>
                </div>
            </form>
        </flux:modal>
    </div>
</div>

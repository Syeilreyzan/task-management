<?php

use Livewire\Component;
use App\Models\Project;
use Livewire\Attributes\On;

new class extends Component
{
    public $id;
    public $project;
    public $name;
    public $description;

    public function mount($id)
    {
        $this->id = $id;
        $this->loadProjects();
    }

    #[On('refreshProject')]
    public function loadProjects()
    {
        $this->project = Project::find($this->id);
    }

    // Display delete project modal
    public function deleteProjectModal($id)
    {
        Flux::modal('delete-project-modal')->show();
    }

    // function delete project
    public function confirmDeleteProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        Flux::toast('Project deleted successfully!');
        Flux::modal('delete-project-modal')->close();
        return redirect()->route('dashboard');
    }


    public function editProject($id)
    {
        $project = Project::findOrFail($id);

        $this->name = $project->name;
        $this->description = $project->description;
        Flux::modal('edit-project-modal')->show();
    }

    public function saveProject()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Project::where('id', $this->id)->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        $this->dispatch('refreshProject');

        Flux::toast('Project updated successfully!');
        Flux::modal('edit-project-modal')->close();
    }
};
?>

<div>
    <div class="lg:p-6 flex flex-col gap-6">
        <a href="{{ route('dashboard') }}" class="">
            <flux:button variant="outline" size="sm" class="flex items-center border-gray-400! hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer group">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <flux:text class="group-hover:text-blue-600 transition-colors">Back to Dashboard</flux:text>
            </flux:button>
        </a>

        <div class="flex items-start justify-between gap-2">
            <div class="flex flex-col gap-2">
                <flux:header class="font-bold text-txt-main text-2xl px-0!">
                    {{ $project->name }} Details
                </flux:header>

                <flux:text>
                    {{ $project->description ?? 'No description provided.' }}
                </flux:text>
            </div>

            @can('manage-projects')
                <!-- desktop action buttons -->
                <div class="hidden lg:flex gap-2">
                    <!-- Edit button -->
                    <flux:tooltip content="Edit project">
                        <flux:button
                            wire:click="editProject({{ $project->id }})"
                            variant="outline"
                            class="flex! items-center cursor-pointer border-gray-400! hover:text-yellow-500 hover:border-yellow-500! transition-colors"
                        >
                            <x-icons.pencil-square class="w-5 h-5" />
                        </flux:button>
                    </flux:tooltip>

                    <!-- Delete button -->
                    <flux:tooltip content="Delete project">
                        <flux:button variant="outline"
                            wire:click="deleteProjectModal({{ $project->id }})"
                            class="flex! items-center cursor-pointer border-gray-400! hover:text-red-500 hover:border-red-500! transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </flux:button>
                    </flux:tooltip>
                </div>

                <!-- Mobile action buttons -->
                <div class="flex lg:hidden gap-2">
                    <!-- Edit button -->
                    <flux:tooltip content="Edit project">
                        <flux:button
                            wire:click="editProject({{ $project->id }})"
                            variant="outline"
                            size="sm"
                            class="flex! items-center cursor-pointer border-gray-400! hover:text-yellow-500 hover:border-yellow-500! transition-colors"
                        >
                            <x-icons.pencil-square class="w-5 h-5" />
                        </flux:button>
                    </flux:tooltip>

                    <!-- Delete button -->
                    <flux:tooltip content="Delete project">
                        <flux:button variant="outline"
                            wire:click="deleteProjectModal({{ $project->id }})"
                            size="sm"
                            class="flex! items-center cursor-pointer border-gray-400! hover:text-red-500 hover:border-red-500! transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </flux:button>
                    </flux:tooltip>
                </div>
            @endcan
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-y-4 lg:gap-4">
            <div class="col-span-2">
                <livewire:tasks.show :id="$project->id" />
            </div>

            <div class="col-span-1">
                <livewire:tasks.members :projectId="$project->id" />
            </div>
        </div>

        <!-- Edit modal -->
        <flux:modal name="edit-project-modal" class="md:w-96">
            <form wire:submit="saveProject" class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Project</flux:heading>
                </div>

                <flux:input wire:model="name" label="Name" placeholder="Enter project name" :invalid="$errors->has('name')"/>
                <flux:input wire:model="description" label="Description" placeholder="Enter project description" :invalid="$errors->has('description')"/>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </flux:modal>

        <!-- Delete modal -->
        <flux:modal name="delete-project-modal" class="md:w-96">
            <form wire:submit="confirmDeleteProject({{ $this->id }})" class="space-y-6">
                <div>
                    <flux:heading size="lg">Confirm delete project?</flux:heading>
                </div>

                <div class="flex justify-end gap-4">
                    <flux:button variant="danger" type="submit" size="sm" class="border-red-500! hover:text-red-700 hover:border-red-700! transition-colors">
                        Delete
                    </flux:button>

                    <flux:button variant="outline" size="sm" class="border-gray-400! hover:text-gray-600 hover:border-gray-600! transition-colors"
                        x-on:click="$flux.modal('delete-project-modal').close()">
                        Cancel
                    </flux:button>
            </form>
        </flux:modal>
    </div>
</div>

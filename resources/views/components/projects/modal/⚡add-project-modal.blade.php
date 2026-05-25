<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Project;

new class extends Component
{
    #[Validate('required', message: 'Please provide a project name')]
    public $name;

    #[Validate('nullable')]
    public $description;

    public function createProjectModal()
    {
        Flux::modal('create-project')->show();
    }

    public function createProject()
    {
        // Validate input
        $this->validate();

        // Create project logic here
        // For example:
        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'project_admin_id' => auth()->id(),
        ]);

        // Close modal after creation
        Flux::modal('create-project')->close();

        $this->name = '';
        $this->description = '';

        // Optionally, emit an event to refresh the project list
        $this->dispatch('projectCreated');
    }


};
?>

<div>
    <flux:button variant="outline"
        size="sm"
        class="flex items-center border-gray-400! text-blue-600 hover:text-blue-600 hover:border-blue-600! transition-colors cursor-pointer" wire:click="$emit('openModal', 'add-project-modal')"
        wire:click="createProjectModal">
        Create New Project
    </flux:button>

    <flux:modal name="create-project" class="md:w-96">
        <form wire:submit="createProject" class="space-y-6">
            <div>
                <flux:heading size="lg">Create Project</flux:heading>
                <flux:text class="mt-2">Add a new project to your workspace.</flux:text>
            </div>
            <flux:input label="Project Name" placeholder="Enter project name" wire:model="name" />
            <flux:input label="Description" placeholder="Enter project description" wire:model="description" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

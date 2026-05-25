<?php

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\Validate;

new class extends Component
{
    public $projectId;

    #[Validate('required|string|max:255', 'Enter a valid task name')]
    public $name;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    public function submit()
    {
        $this->validate();

        Task::create([
            'project_id' => $this->projectId,
            'name' => $this->name,
            'priority' => Task::where('project_id', $this->projectId)->max('priority') + 1,
        ]);

        Flux::toast('Task created successfully!');

        Flux::modals()->close();

        $this->dispatch('refreshTasks');

        $this->reset('name');
    }
};
?>

<div>
    <flux:modal.trigger name="add-task">
        <flux:button size="sm" variant="outline" class="flex items-center" class="text-sm cursor-pointer border-gray-400! hover:text-blue-600 hover:border-blue-600! transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Task
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-task" class="md:w-96">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="lg">Add New Task</flux:heading>
            </div>

            <flux:input wire:model.live="name" label="Name" placeholder="Enter task name" :invalid="$errors->has('name')"/>
            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

<?php

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\On;

new class extends Component
{
    public $id;
    public $editingTaskId;
    public $name;

    public function mount($id)
    {
        $this->id = $id;
    }

    #[On('refreshTasks')]
    public function refresh(){}

    public function with(): array
    {
        return [
            'items' => Task::where('project_id', $this->id)->orderBy('priority', 'asc')->get()
        ];
    }

    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }
    }

    public function editTask($id)
    {
        $task = Task::findOrFail($id);

        $this->editingTaskId = $task->id;
        $this->name = $task->name;

        Flux::modal('edit-task-modal')->show();
    }

    public function saveTask()
    {
        $this->validate([
            'name' => 'required|string|max:255'
        ]);

        Task::where('id', $this->editingTaskId)->update([
            'name' => $this->name
        ]);

        Flux::toast('Task updated successfully!');
        Flux::modal('edit-task-modal')->close();
        $this->reset('editingTaskId', 'name');
    }

    public function deleteTask($id)
    {
        $this->editingTaskId = $id;
        Flux::modal('delete-task-modal')->show();
    }

    public function confirmDeleteTask($id)
    {
        $task = Task::findOrFail($id);

        Task::where('project_id', $task->project_id)
            ->where('priority', '>', $task->priority)
            ->decrement('priority');
        $task->delete();

        Flux::toast('Task deleted and priorities updated!');
        $this->dispatch('refreshTasks');
        Flux::modal('delete-task-modal')->close();
        $this->reset('editingTaskId');
    }

    public function closeDeleteModal()
    {
        Flux::modal('delete-task-modal')->close();
        $this->reset('editingTaskId');
    }
};
?>

<div class="w-full mx-auto p-3 lg:p-6 border-accent-foreground bg-surface rounded-xl border shadow-sm">
    <div class="mb-2 lg:mb-4">
        <livewire:tasks.modal.add-new-task :projectId="$id" />
    </div>

    <ul
        x-data="{
            draggedId: null,
            items: [],

            init() {
                this.syncItems();
            },

            syncItems() {
                this.items = Array.from($el.querySelectorAll('li[data-id]')).map(el => Number(el.getAttribute('data-id')));
            },

            moveItem(targetId) {
                if (this.draggedId === targetId) return;

                this.syncItems();

                const fromIndex = this.items.indexOf(Number(this.draggedId));
                const toIndex = this.items.indexOf(Number(targetId));

                this.items.splice(toIndex, 0, this.items.splice(fromIndex, 1)[0]);

                $wire.updateOrder(this.items);
            }
        }"
        class="space-y-2"
    >
        @forelse($items as $item)
            <li
                draggable="true"
                data-id="{{ $item->id }}"

                x-on:dragstart="draggedId = $el.getAttribute('data-id'); $el.style.opacity = '0.4';"
                x-on:dragend="$el.style.opacity = '1'; draggedId = null;"
                x-on:dragover.prevent="$el.classList.add('border-blue-500', 'bg-blue-50')"
                x-on:dragleave="$el.classList.remove('border-blue-500', 'bg-blue-50')"
                x-on:drop="
                    $el.classList.remove('border-blue-500', 'bg-blue-50');
                    moveItem($el.getAttribute('data-id'));
                "

                class="flex items-center justify-between p-3 bg-accent-foreground dark:bg-zinc-700 rounded-lg shadow-sm transition-all cursor-move select-none hover:border-blue-600! hover:shadow-[0_0_7px_2px_rgba(59,130,246,0.5)] hover:shadow-blue-600"
            >
                <div class="flex items-center gap-3">
                    <div class="text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-1 lg:items-center">
                        <flux:text class="text-sm font-medium text-txt-main capitalize">{{ $item->name }}</flux:text>
                        <flux:text class="text-xs text-txt-muted">({{ $item->created_at->diffForHumans() }})</flux:text>
                    </div>
                </div>

                <div class="flex gap-1">
                    <flux:badge color="gray" size="sm" class="capitalize">
                        Priority: {{ $item->priority }}
                    </flux:badge>

                    @can('manage-projects')
                        <!--Desktop action buttons -->
                        <div class="hidden lg:flex gap-1">
                            <!-- Edit button -->
                            <flux:tooltip content="Edit task">
                                <flux:button
                                    wire:click="editTask({{ $item->id }})"
                                    variant="outline"
                                    size="sm"
                                    class="text-sm cursor-pointer border-gray-400! hover:text-yellow-600 hover:border-yellow-600! transition-colors"
                                >
                                    <x-icons.pencil-square class="w-4 h-4" />
                                </flux:button>
                            </flux:tooltip>

                            <!-- Delete button -->
                            <flux:tooltip content="Delete task">
                                <flux:button
                                    variant="outline"
                                    size="sm"
                                    class="text-sm cursor-pointer border-gray-400! hover:text-red-500 hover:border-red-500! transition-colors"
                                    wire:click="deleteTask({{ $item->id }})"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </flux:button>
                            </flux:tooltip>
                        </div>

                        <!--Mobile action buttons -->
                        <div class="flex lg:hidden">
                            <flux:dropdown>
                                <flux:button icon:trailing="chevron-down" size="sm"></flux:button>

                                <flux:menu>
                                    <flux:menu.item icon="pencil-square" wire:click="editTask({{ $item->id }})">Edit task</flux:menu.item>
                                    <flux:menu.item icon="trash" wire:click="deleteTask({{ $item->id }})">Delete task</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    @endcan
                </div>
            </li>
        @empty
            <li class="text-center py-6 text-sm text-txt-muted italic border border-gray-400 rounded-lg border-dashed bg-accent-foreground dark:bg-zinc-700">
                No items found to rearrange.
            </li>
        @endforelse
    </ul>

    <!-- Edit modal -->
    <flux:modal name="edit-task-modal" class="md:w-96">
        <form wire:submit="saveTask" class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Task</flux:heading>
            </div>

            <flux:input wire:model="name" label="Name" placeholder="Enter task name" :invalid="$errors->has('name')"/>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- delete modal -->
    <flux:modal name="delete-task-modal" class="md:w-96">
        <form wire:submit="confirmDeleteTask({{ $this->editingTaskId }})" class="space-y-6">
            <div>
                <flux:heading size="lg">Confirm delete task?</flux:heading>
            </div>

            <div class="flex gap-4">
                <flux:spacer />
                <flux:button variant="danger" type="submit" size="sm" class="border-red-500! hover:text-red-700 hover:border-red-700! transition-colors">Delete Task</flux:button>
                <flux:button variant="outline" size="sm" class="border-gray-400! hover:text-gray-600 hover:border-gray-600! transition-colors"
                    wire:click="Flux::modal('delete-project-modal')->close()">
                    Cancel
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>

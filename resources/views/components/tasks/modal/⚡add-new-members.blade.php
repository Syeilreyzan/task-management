<?php

use Livewire\Component;
use App\Models\User;

new class extends Component
{
    public $projectId;
    public $users;
    public $userId;

    public function submit()
    {
        $user = User::find($this->userId);

        if ($user) {
            $user->projects()->attach($this->projectId);

            Flux::toast('Member added successfully!');

            Flux::modals()->close();

            $this->dispatch('refreshTeams');
        }
    }

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->users = User::query()
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('projects', function ($query) {
                $query->where('project_id', $this->projectId);
            })
            ->get();
    }

};
?>

<div>
    <flux:modal.trigger name="add-team">
        <flux:button icon="user-plus" size="sm" variant="outline" class="flex! items-center! text-sm cursor-pointer border-gray-400! hover:text-blue-600 hover:border-blue-600! transition-colors">
            <p class="flex">Add New Member</p>
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-team" class="md:w-96">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="lg">Add Team Members</flux:heading>
            </div>

            <flux:select wire:model.live="userId" placeholder="Select a user to add">
                <flux:select.option value="">Select a user to add</flux:select.option>
                @foreach ($users as $user)
                    <flux:select.option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->email }})
                    </flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

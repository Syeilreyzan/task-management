<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\On;


new class extends Component
{
    public $userName = '';
    public $role = '';
    public $deleteUserId;

    public function mount()
    {
        $this->userName = auth()->user()->name;
        $this->role = auth()->user()->role;
    }

    #[On('refreshUsers','projectCreated')]
    public function refresh(){}

    public function with(): array
    {
        $allUsers = User::get() ?? collect();

        $sortedUsers = $allUsers->sortBy(function ($user) {
            return !$user->is(auth()->user());
        });

        return [
            'projects' => auth()->user()->managedProjects()->with('teams')->get() ?? collect(),
            'memberProjects' => auth()->user()->projects()->with('teams')->get() ?? collect(),
            'users' => $sortedUsers,
        ];
    }
};
?>

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-3">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Hello, {{ $userName }}</h1>
            <div>
                <flux:badge color="blue" size="lg" class="capitalize">{{ $role }}</flux:badge>
            </div>
        </div>

        @can('manage-projects')
            <flux:header class="px-0!">Admin Project Management Tools</flux:header>
        @else
            <flux:header class="px-0!">Viewing workspace as standard member.</flux:header>
        @endcan
    </div>

    @can('manage-projects')
        <livewire:admin.index  :projects="$projects" :memberProjects="$memberProjects" :users="$users"/>
    @else
        <livewire:members.index :projects="$memberProjects" />
    @endcan
</div>

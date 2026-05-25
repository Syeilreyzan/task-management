<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use Flux\Flux;

new class extends Component
{
    #[Validate('required', message: 'Please provide a user name')]
    public $userName = '';

    #[Validate('required|email|unique:users', message: 'Please provide a valid and unique email address')]
    public $email = '';

    #[Validate('required|string|min:8', message: 'Password must be at least 8 characters long')]
    public $password = 'password';

    public function submit()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->userName,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        Flux::toast('User created successfully!');

        Flux::modals()->close();

        $this->dispatch('refreshUsers');
    }
};
?>

<div>
    <flux:modal.trigger name="add-user">
        <flux:button class="hover:text-blue-600 hover:border-blue-600! border-gray-400! transition-colors cursor-pointer">Register New User</flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-user" class="md:w-96">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="lg">Register New User</flux:heading>
            </div>

            <flux:input wire:model.live="userName" label="Name" placeholder="Enter user name" :invalid="$errors->has('userName')"/>

            <flux:input wire:model.live="email" label="Email" type="email" placeholder="Enter email" :invalid="$errors->has('email')"/>

            <flux:input wire:model.live="password" label="Password" type="text" placeholder="Default 'password'" :invalid="$errors->has('password')" disabled/>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

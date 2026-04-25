<?php

namespace App\Livewire\ClientUsers;

use App\Services\UserService;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatePage extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $client_id = null;

    protected UserService $userService;
    protected $listeners = ['itemSelected'];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function itemSelected($data)
    {
        $this->{$data['name']} = $data['value'];
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    public function save()
    {
        $this->authorize('create', User::class);
        $this->validate();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => UserRole::CLIENT->value,
            'client_id' => $this->client_id,
            'active' => true, 
        ];

        try {
            $this->userService->storeNewUser($data);
            session()->flash('success', 'App User successfully created!');
            return redirect()->route('client-users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating user.');
        }
    }

    public function render()
    {
        $this->authorize('create', User::class);
        return view('livewire.client-users.create-page');
    }
}
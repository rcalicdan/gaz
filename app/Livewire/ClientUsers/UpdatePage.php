<?php

namespace App\Livewire\ClientUsers;

use App\Services\UserService;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePage extends Component
{
    public User $user;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $password = '';
    public $client_id = null;
    public $active;

    protected UserService $userService;
    protected $listeners = ['itemSelected'];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->client_id = $user->client_id;
        $this->active = $user->active;
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
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'password' => 'nullable|min:8',
            'client_id' => 'required|exists:clients,id',
            'active' => 'required|boolean',
        ];
    }

    public function update()
    {
        $this->authorize('update', $this->user);
        $this->validate();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'client_id' => $this->client_id,
            'active' => $this->active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        try {
            $this->userService->updateUserInformation($this->user, $data);
            session()->flash('success', 'User updated successfully!');
            return redirect()->route('client-users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating user.');
        }
    }

    public function render()
    {
        $this->authorize('update', $this->user);
        return view('livewire.client-users.update-page');
    }
}
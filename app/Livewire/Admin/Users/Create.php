<?php
namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'editor';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.create', [
            'roles' => Role::pluck('name'),
        ])->layout('components.layouts.app');
    }
}

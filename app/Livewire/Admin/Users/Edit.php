<?php
namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;

    public string $name;
    public string $email;
    public string $role;
    public string $password = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? 'editor';
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:8',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            ...($this->password ? ['password' => Hash::make($this->password)] : []),
        ]);

        $this->user->syncRoles([$this->role]);

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.edit', [
            'roles' => Role::pluck('name'),
        ])->layout('components.layouts.app');
    }
}

<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public function render()
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.users.index', [
            'users' => $users,

            // broj unikatnih rola u sistemu
            'uniqueRoles' => $users
                ->flatMap(fn ($u) => $u->roles->pluck('name'))
                ->unique()
                ->count(),

            // broj admina (super_admin)
            'adminCount' => $users
                ->filter(fn ($u) => $u->hasRole('super_admin'))
                ->count(),
        ])->layout('components.layouts.app');
    }
}

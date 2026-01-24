<?php

namespace App\Livewire\Admin\System;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    public function render()
    {
        $roles = Role::query()
            ->orderBy('name')
            ->get()
            ->map(fn ($r) => [
                'name' => $r->name,
                'guard' => $r->guard_name,
                'users_count' => method_exists($r, 'users') ? $r->users()->count() : null,
            ]);

        return view('livewire.admin.system.roles', compact('roles'))
            ->layout('components.layouts.app')
            ->title('System • Roles');
    }
}

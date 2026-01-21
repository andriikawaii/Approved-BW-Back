<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $q = '';
    public string $status = 'all'; // all | active | inactive
    public string $sort = 'updated_at';
    public string $dir = 'desc';

    protected $queryString = [
        'q' => ['except' => ''],
        'status' => ['except' => 'all'],
        'sort' => ['except' => 'updated_at'],
        'dir' => ['except' => 'desc'],
    ];

    public function updatingQ(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function setSort(string $field): void
    {
        if ($this->sort === $field) {
            $this->dir = $this->dir === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sort = $field;
        $this->dir = 'asc';
    }

    public function toggleActive(int $id): void
    {
        $r = Redirect::findOrFail($id);
        $r->update([
            'is_active' => !$r->is_active,
            'updated_by' => auth()->id(),
        ]);
    }

    public function render()
    {
        $query = Redirect::query();

        if ($this->q !== '') {
            $query->where(function ($q) {
                $q->where('from_path', 'like', "%{$this->q}%")
                    ->orWhere('to_path', 'like', "%{$this->q}%");
            });
        }

        if ($this->status === 'active') {
            $query->where('is_active', true);
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($this->sort, $this->dir);

        return view('livewire.admin.redirects.index', [
            'redirects' => $query->paginate(15),
        ])->layout('components.layouts.app');
    }
}

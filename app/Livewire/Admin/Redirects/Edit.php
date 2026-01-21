<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Redirect $redirect;

    public string $from_path = '';
    public string $to_path = '';
    public int $status_code = 301;
    public bool $is_active = true;

    public function mount(Redirect $redirect): void
    {
        $this->redirect = $redirect;

        $this->from_path = $redirect->from_path;
        $this->to_path = $redirect->to_path;
        $this->status_code = (int) $redirect->status_code;
        $this->is_active = (bool) $redirect->is_active;
    }

    protected function rules(): array
    {
        return [
            'from_path' => [
                'required', 'string', 'max:255',
                Rule::unique('redirects', 'from_path')->ignore($this->redirect->id),
            ],
            'to_path' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308'],
            'is_active' => ['boolean'],
        ];
    }

    protected function normalize(string $path): string
    {
        $path = trim($path);
        $path = '/' . ltrim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function save()
    {
        $this->from_path = $this->normalize($this->from_path);
        $this->to_path = $this->normalize($this->to_path);

        if ($this->from_path === $this->to_path) {
            $this->addError('to_path', 'To path cannot be the same as From path.');
            return;
        }

        $this->validate();

        $this->redirect->update([
            'from_path' => $this->from_path,
            'to_path' => $this->to_path,
            'status_code' => $this->status_code,
            'is_active' => $this->is_active,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.redirects.index');
    }

    public function render()
    {
        return view('livewire.admin.redirects.edit')
            ->layout('components.layouts.app');
    }
}

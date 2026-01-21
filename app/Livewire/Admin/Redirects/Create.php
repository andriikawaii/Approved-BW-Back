<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public string $from_path = '';
    public string $to_path = '';
    public int $status_code = 301;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'from_path' => [
                'required', 'string', 'max:255',
                Rule::unique('redirects', 'from_path'),
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

        Redirect::create([
            'from_path' => $this->from_path,
            'to_path' => $this->to_path,
            'status_code' => $this->status_code,
            'is_active' => $this->is_active,
            'hits' => 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.redirects.index');
    }

    public function render()
    {
        return view('livewire.admin.redirects.create')
            ->layout('components.layouts.app');
    }
}

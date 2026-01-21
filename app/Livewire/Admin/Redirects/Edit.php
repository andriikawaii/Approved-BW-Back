<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use Illuminate\Validation\Rule;
use Livewire\Component;

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

        $this->from_path   = $redirect->from_path;
        $this->to_path     = $redirect->to_path;
        $this->status_code = (int) $redirect->status_code;
        $this->is_active   = (bool) $redirect->is_active;
    }

    protected function rules(): array
    {
        return [
            'from_path' => [
                'required', 'string', 'max:255',
                Rule::unique('redirects', 'from_path')->ignore($this->redirect->id),
            ],
            'to_path' => ['required', 'string', 'max:2048'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308'],
            'is_active' => ['boolean'],
        ];
    }

    protected function normalizeFrom(string $path): string
    {
        $path = trim($path);
        $path = '/' . ltrim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    protected function normalizeTo(string $to): string
    {
        $to = trim($to);

        if (preg_match('#^https?://#i', $to)) {
            return rtrim($to, '/');
        }

        $to = '/' . ltrim($to, '/');
        return $to === '/' ? '/' : rtrim($to, '/');
    }

    protected function validateToFormat(): bool
    {
        if (
            str_starts_with($this->to_path, '/') ||
            preg_match('#^https?://#i', $this->to_path)
        ) {
            return true;
        }

        $this->addError('to_path', 'To path mora biti relativan path (/nešto) ili full URL (https://...).');
        return false;
    }

    protected function validateLoopProtection(): bool
    {
        if ($this->from_path === $this->to_path) {
            $this->addError('to_path', 'To path ne može biti isti kao From path.');
            return false;
        }

        $reverseExists = Redirect::query()
            ->where('from_path', $this->to_path)
            ->where('to_path', $this->from_path)
            ->where('id', '!=', $this->redirect->id)
            ->exists();

        if ($reverseExists) {
            $this->addError('to_path', 'Ovo pravi redirect loop (već postoji reverse redirect).');
            return false;
        }

        return true;
    }

    public function save()
    {
        $this->from_path = $this->normalizeFrom($this->from_path);
        $this->to_path   = $this->normalizeTo($this->to_path);

        if (!$this->validateToFormat()) {
            return;
        }

        if (!$this->validateLoopProtection()) {
            return;
        }

        $this->validate();

        $this->redirect->update([
            'from_path'   => $this->from_path,
            'to_path'     => $this->to_path,
            'status_code' => $this->status_code,
            'is_active'   => $this->is_active,
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('admin.redirects.index');
    }

    public function render()
    {
        return view('livewire.admin.redirects.edit')
            ->layout('components.layouts.app');
    }
}

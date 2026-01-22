<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;
use Illuminate\Validation\Rule;
use Livewire\Component;

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
                'required',
                'string',
                'max:255',
                Rule::unique('redirects', 'from_path'),
            ],
            'to_path' => ['required', 'string', 'max:2048'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308'],
            'is_active' => ['boolean'],
        ];
    }

    protected function validateToFormat(): bool
    {
        if (
            str_starts_with($this->to_path, '/') ||
            preg_match('#^https?://#i', $this->to_path)
        ) {
            return true;
        }

        $this->addError(
            'to_path',
            'To path mora biti relativan path (/nešto) ili full URL (https://...).'
        );

        return false;
    }

    protected function validateLoopProtection(): bool
    {
        if ($this->from_path === $this->to_path) {
            $this->addError(
                'to_path',
                'To path ne može biti isti kao From path.'
            );
            return false;
        }

        $reverseExists = Redirect::query()
            ->where('from_path', $this->to_path)
            ->where('to_path', $this->from_path)
            ->exists();

        if ($reverseExists) {
            $this->addError(
                'to_path',
                'Ovo pravi redirect loop (već postoji reverse redirect).'
            );
            return false;
        }

        return true;
    }

    public function save()
    {
        // ✅ normalizacija (centralno)
        $this->from_path = RedirectPathNormalizer::from($this->from_path);
        $this->to_path   = RedirectPathNormalizer::to($this->to_path);

        if (!$this->validateToFormat()) {
            return;
        }

        if (!$this->validateLoopProtection()) {
            return;
        }

        $this->validate();

        Redirect::create([
            'from_path'   => $this->from_path,
            'to_path'     => $this->to_path,
            'status_code' => $this->status_code,
            'is_active'   => $this->is_active,
            'hits'        => 0,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('admin.redirects.index');
    }

    public function render()
    {
        return view('livewire.admin.redirects.create')
            ->layout('components.layouts.app');
    }
}

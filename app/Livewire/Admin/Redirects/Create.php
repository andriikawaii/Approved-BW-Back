<?php

namespace App\Livewire\Admin\Redirects;

use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    private const MAX_REDIRECT_DEPTH = 10;

    public string $from_path = '';
    public string $to_path = '';
    public string $type = '301';
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
            'type' => ['required', 'string', 'in:301,302'],
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
            'To path must be a relative path (/something) or full URL (https://...).'
        );

        return false;
    }

    protected function validateLoopProtection(): bool
    {
        if ($this->from_path === $this->to_path) {
            $this->addError(
                'to_path',
                'To path cannot be the same as From path.'
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
                'This would create a redirect loop (reverse redirect already exists).'
            );
            return false;
        }

        if ($this->createsRedirectLoop()) {
            $this->addError(
                'to_path',
                'This would create a redirect chain loop.'
            );
            return false;
        }

        return true;
    }

    protected function createsRedirectLoop(): bool
    {
        if (preg_match('#^https?://#i', $this->to_path)) {
            return false;
        }

        $visited = [
            $this->from_path => true,
        ];

        $current = RedirectPathNormalizer::from($this->to_path);

        for ($depth = 0; $depth < self::MAX_REDIRECT_DEPTH; $depth++) {
            if (isset($visited[$current])) {
                return true;
            }

            $visited[$current] = true;

            $redirect = Redirect::query()
                ->where('from_path', $current)
                ->first(['to_path']);

            if (!$redirect) {
                return false;
            }

            if (preg_match('#^https?://#i', $redirect->to_path)) {
                return false;
            }

            $current = RedirectPathNormalizer::from($redirect->to_path);
        }

        return true;
    }

    public function save()
    {
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
            'from_path' => $this->from_path,
            'to_path'   => $this->to_path,
            'type'      => $this->type,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('admin.redirects.index');
    }

    public function render()
    {
        return view('livewire.admin.redirects.create')
            ->layout('components.layouts.app');
    }
}

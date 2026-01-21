<?php

namespace App\Livewire\Admin\Media;

use Livewire\Component;
use App\Models\MediaAsset;

class Picker extends Component
{
    public bool $open = false;
    public string $search = '';
    public ?int $targetIndex = null;

    protected $listeners = [
        'openMediaPicker' => 'open',
    ];

    public function open(int $index): void
    {
        $this->targetIndex = $index;
        $this->open = true;
    }

    public function select(int $mediaId): void
    {
        $this->dispatch(
            'media-selected',
            index: $this->targetIndex,
            mediaId: $mediaId
        );

        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.media.picker', [
            'media' => MediaAsset::query()
                ->when($this->search, fn ($q) =>
                $q->where('file_name', 'like', "%{$this->search}%")
                )
                ->limit(24)
                ->get(),
        ]);
    }
}

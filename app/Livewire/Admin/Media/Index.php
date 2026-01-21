<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function delete(int $id): void
    {
        $media = MediaAsset::findOrFail($id);

        // obriši fajl
        if ($media->file_path) {
            \Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();
    }

    public function render()
    {
        return view('livewire.admin.media.index', [
            'media' => MediaAsset::latest()->paginate(24),
        ]);
    }
}

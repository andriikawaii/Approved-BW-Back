<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;

class Create extends Component
{
    use WithFileUploads;

    public $file;
    public $title;
    public $alt_text;

    protected $rules = [
        'file' => 'required|file|max:10240', // 10MB
        'title' => 'nullable|string|max:255',
        'alt_text' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        $path = $this->file->store('media', 'public');

        MediaAsset::create([
            'file_name' => $this->file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $this->file->getMimeType(),
            'size' => $this->file->getSize(),
            'title' => $this->title,
            'alt_text' => $this->alt_text,
        ]);

        return redirect()->route('admin.media.index');
    }

    public function removeFile(): void
    {
        $this->reset('file');
    }

    public function isImageFile(): bool
    {
        if (! $this->file instanceof TemporaryUploadedFile) {
            return false;
        }

        $mime = $this->file->getMimeType();

        return is_string($mime) && str_starts_with($mime, 'image/');
    }

    public function getImageSize(): ?array
    {
        if (! $this->isImageFile()) {
            return null;
        }

        try {
            $realPath = $this->file->getRealPath();
            if (! $realPath) {
                return null;
            }

            $info = @getimagesize($realPath);
            if (! is_array($info) || ! isset($info[0], $info[1])) {
                return null;
            }

            return [
                'width' => (int) $info[0],
                'height' => (int) $info[1],
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.admin.media.create');
    }
}

<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Create extends Component
{
    use WithFileUploads;

    /** @var TemporaryUploadedFile[] */
    public $photos = [];

    /** @var array<int, string> */
    public $altTexts = [];

    public function updatedPhotos(): void
    {
        $this->validate([
            'photos.*' => 'file|max:102400',
        ]);

        // Generate alt texts for each new file
        foreach ($this->photos as $index => $file) {
            if (!isset($this->altTexts[$index])) {
                $this->altTexts[$index] = self::generateAltFromFilename($file->getClientOriginalName());
            }
        }
    }

    public static function generateAltFromFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', trim($name));
        $name = ucfirst($name);

        return $name;
    }

    public function removeFile(int $index): void
    {
        $photos = collect($this->photos)->values()->all();
        $alts = collect($this->altTexts)->values()->all();

        unset($photos[$index]);
        unset($alts[$index]);

        $this->photos = array_values($photos);
        $this->altTexts = array_values($alts);
    }

    public function save()
    {
        \Log::info('Media upload save() called', [
            'photos_count' => count($this->photos),
            'photos_types' => array_map(fn($f) => get_class($f), $this->photos),
            'altTexts' => $this->altTexts,
        ]);

        if (empty($this->photos)) {
            $this->addError('photos', 'Please select at least one file.');
            return;
        }

        $count = 0;

        foreach ($this->photos as $index => $file) {
            if (!$file instanceof TemporaryUploadedFile) {
                \Log::warning('Media upload: file at index ' . $index . ' is not TemporaryUploadedFile, type: ' . get_class($file));
                continue;
            }

            try {
                $path = $file->store('media', 'public');
                \Log::info('Media upload: stored file at ' . $path);

                $alt = trim($this->altTexts[$index] ?? '');
                if ($alt === '') {
                    $alt = self::generateAltFromFilename($file->getClientOriginalName());
                }

                MediaAsset::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'title' => null,
                    'alt_text' => $alt,
                ]);

                $count++;
            } catch (\Throwable $e) {
                \Log::error('Media upload error: ' . $e->getMessage());
            }
        }

        session()->flash('message', $count . ' file(s) uploaded successfully.');

        return redirect()->route('admin.media.index');
    }

    public function isImageFile(int $index): bool
    {
        $file = $this->photos[$index] ?? null;
        if (!$file instanceof TemporaryUploadedFile) {
            return false;
        }
        $mime = $file->getMimeType();
        return is_string($mime) && str_starts_with($mime, 'image/');
    }

    public function isVideoFile(int $index): bool
    {
        $file = $this->photos[$index] ?? null;
        if (!$file instanceof TemporaryUploadedFile) {
            return false;
        }
        $mime = $file->getMimeType();
        if (is_string($mime) && str_starts_with($mime, 'video/')) {
            return true;
        }
        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
    }

    public function render()
    {
        return view('livewire.admin.media.create');
    }
}

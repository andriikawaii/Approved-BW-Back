<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;

class Create extends Component
{
    use WithFileUploads;

    /** @var TemporaryUploadedFile[] */
    public $files = [];

    /** @var array<int, string> */
    public $altTexts = [];

    protected function rules(): array
    {
        return [
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,mp4,mov,avi,webm',
            'altTexts.*' => 'nullable|string|max:255',
        ];
    }

    public function updatedFiles(): void
    {
        $this->validateOnly('files.*');

        // Generate alt texts for newly added files
        foreach ($this->files as $index => $file) {
            if ($file instanceof TemporaryUploadedFile && !isset($this->altTexts[$index])) {
                $this->altTexts[$index] = self::generateAltFromFilename($file->getClientOriginalName());
            }
        }
    }

    public static function generateAltFromFilename(string $filename): string
    {
        // Remove extension
        $name = pathinfo($filename, PATHINFO_FILENAME);
        // Replace dashes and underscores with spaces
        $name = str_replace(['-', '_'], ' ', $name);
        // Trim excess whitespace
        $name = preg_replace('/\s+/', ' ', trim($name));
        // Capitalize first letter
        $name = ucfirst($name);

        return $name;
    }

    public function removeFile(int $index): void
    {
        unset($this->files[$index]);
        unset($this->altTexts[$index]);
        $this->files = array_values($this->files);
        $this->altTexts = array_values($this->altTexts);
    }

    public function save()
    {
        $this->validate();

        foreach ($this->files as $index => $file) {
            if (!$file instanceof TemporaryUploadedFile) {
                continue;
            }

            $path = $file->store('media', 'public');
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
        }

        session()->flash('message', count($this->files) . ' file(s) uploaded successfully.');

        return redirect()->route('admin.media.index');
    }

    public function isImageFile(int $index): bool
    {
        $file = $this->files[$index] ?? null;
        if (!$file instanceof TemporaryUploadedFile) {
            return false;
        }
        $mime = $file->getMimeType();
        return is_string($mime) && str_starts_with($mime, 'image/');
    }

    public function render()
    {
        return view('livewire.admin.media.create');
    }
}

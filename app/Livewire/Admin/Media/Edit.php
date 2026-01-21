<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Livewire\Component;

class Edit extends Component
{
    public MediaAsset $mediaAsset;

    public string|null $title = null;
    public string|null $alt_text = null;

    public function mount(MediaAsset $mediaAsset)
    {
        $this->mediaAsset = $mediaAsset;
        $this->title = $mediaAsset->title;
        $this->alt_text = $mediaAsset->alt_text;
    }

    public function save()
    {
        $this->mediaAsset->update([
            'title' => $this->title,
            'alt_text' => $this->alt_text,
        ]);

        return redirect()->route('admin.media.index');
    }

    public function render()
    {
        return view('livewire.admin.media.edit');
    }
}

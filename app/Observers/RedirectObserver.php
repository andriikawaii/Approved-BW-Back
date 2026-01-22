<?php

namespace App\Observers;

use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;

class RedirectObserver
{
    public function saving(Redirect $redirect): void
    {
        $redirect->from_path = RedirectPathNormalizer::from($redirect->from_path);
        $redirect->to_path   = RedirectPathNormalizer::to($redirect->to_path);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Converts PNG/JPG images to WebP and resizes oversized images.
 *
 * Usage:
 *   php artisan media:optimize              — dry run (shows what would happen)
 *   php artisan media:optimize --execute    — actually converts files
 *   php artisan media:optimize --force      — reconverts even if WebP already exists
 *
 * The command never deletes originals. WebP files are created alongside them.
 * After running, the API will automatically serve WebP URLs when the frontend
 * requests them (since MediaUrlResolver resolves by file_path).
 */
class OptimizeMedia extends Command
{
    protected $signature = 'media:optimize
                            {--execute : Actually write files (default is dry run)}
                            {--force   : Reconvert even if WebP already exists}
                            {--max-width=2000 : Max width in pixels before resizing}
                            {--quality=80 : WebP quality 1-100}';

    protected $description = 'Convert PNG/JPG images to WebP and resize oversized images';

    /** Directories to scan, relative to the Laravel root. */
    private array $scanDirs = [
        'storage/app/public',
        'public/images',
        'public/hero',
        'public/portfolio',
        'public/team',
    ];

    public function handle(): int
    {
        $execute  = $this->option('execute');
        $force    = $this->option('force');
        $maxWidth = (int) $this->option('max-width');
        $quality  = (int) $this->option('quality');

        if (! $execute) {
            $this->warn('DRY RUN — pass --execute to actually convert files.');
            $this->newLine();
        }

        if (! extension_loaded('gd')) {
            $this->error('GD extension is not loaded. Cannot convert images.');
            return self::FAILURE;
        }

        if (! function_exists('imagewebp')) {
            $this->error('GD is loaded but WebP support is missing (imagewebp not available).');
            return self::FAILURE;
        }

        $converted = 0;
        $skipped   = 0;
        $errors    = 0;

        foreach ($this->scanDirs as $relDir) {
            $absDir = base_path($relDir);

            if (! is_dir($absDir)) {
                $this->line("<fg=gray>Skipping {$relDir} (not found)</>");
                continue;
            }

            $this->info("Scanning {$relDir} ...");

            $files = File::allFiles($absDir);

            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());

                if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    continue;
                }

                $sourcePath = $file->getRealPath();
                $webpPath   = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);

                if (! $force && file_exists($webpPath)) {
                    $skipped++;
                    continue;
                }

                $originalSize = filesize($sourcePath);
                $label        = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $sourcePath);

                if (! $execute) {
                    $this->line("  <fg=yellow>[DRY RUN]</> Would convert: {$label}");
                    $converted++;
                    continue;
                }

                try {
                    $result = $this->convertToWebP($sourcePath, $webpPath, $maxWidth, $quality);

                    if ($result) {
                        $newSize   = filesize($webpPath);
                        $saving    = round((1 - $newSize / $originalSize) * 100);
                        $this->line("  <fg=green>✓</> {$label} — " . $this->humanBytes($originalSize) . ' → ' . $this->humanBytes($newSize) . " ({$saving}% saved)");
                        $converted++;
                    } else {
                        $this->line("  <fg=red>✗</> {$label} — conversion returned false");
                        $errors++;
                    }
                } catch (\Throwable $e) {
                    $this->line("  <fg=red>✗</> {$label} — {$e->getMessage()}");
                    $errors++;
                }
            }
        }

        $this->newLine();

        if ($execute) {
            $this->info("Done. Converted: {$converted} | Skipped (already exist): {$skipped} | Errors: {$errors}");
        } else {
            $this->info("Dry run complete. Would convert {$converted} files. Run with --execute to apply.");
        }

        return self::SUCCESS;
    }

    private function convertToWebP(string $source, string $dest, int $maxWidth, int $quality): bool
    {
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        $image = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($source),
            'png'         => $this->loadPng($source),
            default       => false,
        };

        if (! $image) {
            throw new \RuntimeException("Could not load image: {$source}");
        }

        $width  = imagesx($image);
        $height = imagesy($image);

        // Resize if wider than max-width
        if ($width > $maxWidth) {
            $ratio     = $maxWidth / $width;
            $newWidth  = $maxWidth;
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $result = imagewebp($image, $dest, $quality);
        imagedestroy($image);

        return $result;
    }

    private function loadPng(string $source): \GdImage|false
    {
        $image = imagecreatefrompng($source);

        if (! $image) {
            return false;
        }

        // Convert transparent background to opaque for WebP (optional — WebP supports alpha too)
        // We keep alpha so PNGs with transparency stay transparent in WebP.
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes >= 1_048_576) {
            return round($bytes / 1_048_576, 1) . ' MB';
        }

        return round($bytes / 1024, 1) . ' KB';
    }
}

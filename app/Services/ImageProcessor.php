<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

/**
 * Procesat d'imatges centralitzat i segur en memòria.
 *
 * Cada operació carrega una sola imatge, la desa i allibera immediatament
 * la memòria de GD amb destroy(). Mai es processen diverses imatges alhora.
 */
class ImageProcessor
{
    /**
     * Reescala l'original in-place a un ample màxim (mantenint proporció)
     * i el recomprimeix, sense canviar-ne el format.
     */
    public function optimizeOriginal(string $absPath, int $maxWidth = 1600, int $quality = 82): void
    {
        if (!is_file($absPath)) {
            return;
        }

        // No recomprimim GIF: GD els aplanaria i perdrien l'animació.
        if (strtolower(pathinfo($absPath, PATHINFO_EXTENSION)) === 'gif') {
            return;
        }

        $image = Image::make($absPath);

        if ($image->width() > $maxWidth) {
            $image->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->save($absPath, $quality);
        $image->destroy();
    }

    /**
     * Genera una variant recortada a mida fixa (fit) en format WebP.
     */
    public function makeVariant(string $srcAbs, string $destAbs, int $width, int $height, int $quality = 82): void
    {
        if (!is_file($srcAbs)) {
            return;
        }

        $this->ensureDir(dirname($destAbs));

        $image = Image::make($srcAbs)->fit($width, $height);
        $image->save($destAbs, $quality, 'webp');
        $image->destroy();
    }

    /**
     * Converteix a WebP mantenint la proporció, limitant l'ample màxim.
     * Pensat per a imatges dins del contingut (no es recorten).
     */
    public function convertToWebp(string $srcAbs, string $destAbs, int $maxWidth = 1280, int $quality = 82): void
    {
        if (!is_file($srcAbs)) {
            return;
        }

        $this->ensureDir(dirname($destAbs));

        $image = Image::make($srcAbs);

        if ($image->width() > $maxWidth) {
            $image->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->save($destAbs, $quality, 'webp');
        $image->destroy();
    }

    private function ensureDir(string $dir): void
    {
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0775, true, true);
        }
    }
}
